<?php

namespace App\Http\Controllers;

use App\BankDetails;
use App\DonatedVsItems;
use App\Item;
use App\ItemsPosted;
use App\PartnerToStaff;
use App\SaveCard;
use App\StripeUser;
use App\Token;
use App\User;
use App\UserBankDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\OAuth;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Transfer;
use Stripe\PaymentMethod;

class PaymentController extends Controller
{
    public function donate_via_saved(Request $request){
//        $checkUser=DB::table('role_user')->where('user_id','=',$request->item_data['user_id'])->first()->role_id;
//        if($checkUser == 1 || $checkUser == 2)
//        {
        $getStripeSecret=BankDetails::first()->SECRET_KEY;
//        }else{
//            $getStripeSecret=UserBankDetails::where('user_id','=',$request->item_data['user_id'])->first()->SECRET_KEY;
//        }
        $getConnectedAccountId=StripeUser::where('user_id','=',$request->item_data['user_id'])->first()->connect_id;
        $getPaymentDetails=ItemsPosted::join('users','users.id','=','items_posteds.user_id')->where('items_posteds.id','=',$request->item_data['did'])->first();
        Stripe::setApiKey($getStripeSecret);
        if($request->donatedAmount == "1"){
            $amount =(float) ItemsPosted::where('id','=',$request->item_data['did'])->first()->item_price;
            $saveAmount=$amount;
            $typeAmount="full";
        }
        if($request->donatedAmount == "2"){
            $GetItemData=Item::select(DB::raw('*,items_posteds.id as did,
            SUM(donated_vs_items.donated_amount) as sum_donated_amount'))
                ->join('items_posteds','items_posteds.item_id','=','items.id')
                ->leftJoin('donated_vs_items','donated_vs_items.item_id','=','items_posteds.id')
                ->where('items_posteds.id','=',$request->item_data['did'])
                ->where('donated_vs_items.item_id','=',$request->item_data['did'])
                ->groupBy('donated_vs_items.item_id')
                ->first();
            if($GetItemData == null){
                $amount =(float) ItemsPosted::where('id','=',$request->item_data['did'])->first()->item_price;
                $saveAmount=$amount;
                $typeAmount="full";
            }else{
                $amount =(float) $GetItemData->item_price - (float)$GetItemData->sum_donated_amount;
                $saveAmount=$amount;
                $amount = $this->getAmountForPartnerGET($amount);
                $typeAmount="remaining";
            }

        }
        if($request->donatedAmount == "3"){
            $amount = 1.00;
            $saveAmount=$amount;
            $amount = $this->getAmountForPartnerGET($amount);
            $typeAmount="remaining";

        }
        $checkCardSaved=SaveCard::where('id','=',$request->selectedCard)->first();
        //dd($checkCardSaved);
        if($checkCardSaved == null){
            return response("error");
        }else{
            $GetItemData=Item::select(DB::raw('*,items_posteds.id as did,
            SUM(donated_vs_items.donated_amount) as sum_donated_amount'))
                ->join('items_posteds','items_posteds.item_id','=','items.id')
                ->leftJoin('donated_vs_items','donated_vs_items.item_id','=','items_posteds.id')
                ->where('items_posteds.id','=',$request->item_data['did'])
                ->where('donated_vs_items.item_id','=',$request->item_data['did'])
                ->groupBy('donated_vs_items.item_id')
                ->first();
            if($GetItemData == null){
                $getItemPrice = ItemsPosted::where('id','=',$request->item_data['did'])->first()->item_price;
            }else{
                $getItemPrice = $GetItemData->item_price;
            }
            if(($GetItemData == null ? 0 : $GetItemData->sum_donated_amount) + $saveAmount > $getItemPrice){
                return response("error");
            }else {
                ///Charging
                // $getPaymentDetails=ItemsPosted::join('users','users.id','=','items_posteds.user_id')->where('items_posteds.id','=',$request->item_data['did'])->first();
                $charge = Charge::create([
                    'amount' => round($amount * 100),
                    'currency' => 'usd',
                    'customer' => $checkCardSaved->card_token,
                    'description' => "Item Name: '".$getPaymentDetails->item_description."', User Name: '".$getPaymentDetails->email."' ",
                ]);
                $transfer=Transfer::create([
                    'amount' => round($this->getAmountForPartner($amount,$typeAmount) * 100),
                    'currency' => 'usd',
                    "source_transaction" => $charge->id,
                    'destination' => $getConnectedAccountId,
                    'description' => "Item Name: '" . $getPaymentDetails->item_description . "', User Name: '" . $getPaymentDetails->email . "' ",
                ]);

            }
            if(Auth::user()->roles()->first()->name == "manager" || Auth::user()->roles()->first()->name == "staff"){
                $id=PartnerToStaff::where('staff_id','=',Auth::user()->id)->first()->partner_id;
            }else{
                $id=Auth::user()->id;
            }
            //Save Donation Record
            $saveDonation=new DonatedVsItems();
            $saveDonation->item_id=$request->item_data['did'];
            $saveDonation->donator_id=$id;
            $saveDonation->donated_amount=$saveAmount;
            $saveDonation->token=$charge->id;
            $saveDonation->save();

            //Update Status
            $GetItemData=Item::select(DB::raw('*,items_posteds.id as did,SUM(donated_vs_items.donated_amount) as sum_donated_amount'))
                ->join('items_posteds','items_posteds.item_id','=','items.id')
                ->leftJoin('donated_vs_items','donated_vs_items.item_id','=','items_posteds.id')
                ->where('items_posteds.id','=',$request->item_data['did'])
                ->where('donated_vs_items.item_id','=',$request->item_data['did'])
                ->groupBy('donated_vs_items.item_id')
                ->first();
            //Donator Send Notification

            if($GetItemData->sum_donated_amount < $GetItemData->item_price){
                $status=2;
                $body=Auth::user()->first_name." has donated $".$amount." to help ".$GetItemData->customer_id;
                $title="New Donation";
                $color="text-blue";
            }else if($GetItemData->sum_donated_amount == $GetItemData->item_price){
                $status=0;
                $body=Auth::user()->first_name." has donated $".$amount." and donation amount is completed";
                $title="Donation Request Completed";
                $color="text-green";

            }
            $title=$title;
            $body=$body;
            $type=json_encode(array("fas fa-donate $color",$getPaymentDetails->user_id,'/partner/posted-items'));
            $token=Token::where('user_id','=',$getPaymentDetails->user_id)->pluck('token');
            $icon="/abc.png";
            $sendNotification=new UserController();
            $sendNotification->saveNotification($getPaymentDetails->user_id,$title,$body,$type,$icon);
            $sendNotification->sendPushNotifcations($title,$body,$type,$token,$icon);
            //Updating Status
            $updateinItemPosted=ItemsPosted::where('id','=',$request->item_data['did'])->update(['status' => $status]);


            if($charge->status == "succeeded"){
                return response("success");
            }else{
                return response("error");
            }
        }
    }
    public function donate(Request $request){
        //dd($request->source); die;
//        $checkUser=DB::table('role_user')->where('user_id','=',$request->item_data['user_id'])->first()->role_id;
//        if($checkUser == 1 || $checkUser == 2)
//        {
        $getStripeSecret=BankDetails::first()->SECRET_KEY;
//        }else{
//            $getStripeSecret=UserBankDetails::where('user_id','=',$request->item_data['user_id'])->first()->SECRET_KEY;
//        }
        $getConnectedAccountId=StripeUser::where('user_id','=',$request->item_data['user_id'])->first()->connect_id;
        $getPaymentDetails=ItemsPosted::join('users','users.id','=','items_posteds.user_id')->where('items_posteds.id','=',$request->item_data['did'])->first();
        Stripe::setApiKey($getStripeSecret);
        if(Auth::user()->roles()->first()->name == "manager" || Auth::user()->roles()->first()->name == "staff"){
            $id=PartnerToStaff::where('staff_id','=',Auth::user()->id)->first()->partner_id;
        }else{
            $id=Auth::user()->id;
        }
        //$amount=0;
        if($request->donatedAmount == "1"){
            $amount =(float) ItemsPosted::where('id','=',$request->item_data['did'])->first()->item_price;
            $saveAmount=$amount;
            $typeAmount="full";
        }
        if($request->donatedAmount == "2"){
            $GetItemData=Item::select(DB::raw('*,items_posteds.id as did,
            SUM(donated_vs_items.donated_amount) as sum_donated_amount'))
                ->join('items_posteds','items_posteds.item_id','=','items.id')
                ->leftJoin('donated_vs_items','donated_vs_items.item_id','=','items_posteds.id')
                ->where('items_posteds.id','=',$request->item_data['did'])
                ->where('donated_vs_items.item_id','=',$request->item_data['did'])
                ->groupBy('donated_vs_items.item_id')
                ->first();
            if($GetItemData == null){
                $amount =(float) ItemsPosted::where('id','=',$request->item_data['did'])->first()->item_price;
                $saveAmount=$amount;
                $typeAmount="full";

            }else{
                $amount =(float) $GetItemData->item_price - $GetItemData->sum_donated_amount;
                $saveAmount=$amount;
                $amount = $this->getAmountForPartnerGET($amount);
                $typeAmount="remaining";
            }
        }
        if($request->donatedAmount == "3"){
            $amount = 1.00;
            $saveAmount=$amount;
            $amount = $this->getAmountForPartnerGET($amount);
            $typeAmount="remaining";
            //echo $amount; die;
        }
        if(Auth::guest()){
            $email="no-email@gmail.com";
        }else{
            if(Auth::user()->roles()->first()->name == "manager" || Auth::user()->roles()->first()->name == "staff"){
                $getData=PartnerToStaff::where('staff_id','=',Auth::user()->id)->first()->partner_id;
                $email=User::where('id','=',$getData)->first()->email;
            }else {
                $email = Auth::user()->email;
                $getData=Auth::user()->id;
            }
        }
        if($request->save_card === true) {
            if (Auth::user()->roles()->first()->name == "donor" || Auth::user()->roles()->first()->name == "partner") {

                $checkCardSaved = SaveCard::where('user_id', '=', $id)->first();
                if ($checkCardSaved == null) {
                    ///Getting Customer Card Cards
                    $customer = Customer::create([
                        'source' => $request->source['id'],
                        'email' => $email,
                    ]);
                    //dd($customer);
                    //Save Card
                    $customer_id = $customer->id;
                    $saveCard = new SaveCard();
                    $saveCard->user_id = $getData;
                    $saveCard->card_token = $customer_id;
                    $saveCard->card_name = "Last4 " . $request->source['card']['last4'];
                    $saveCard->role = Auth::user()->roles()->first()->name;
                    $saveCard->description = json_encode($request->source['card']);
                    $saveCard->save();

                } else {
                    $customer_id = $checkCardSaved->card_token;
                }
            } else if (Auth::user()->roles()->first()->name == "superadmin") {
                $checkCardSaved = SaveCard::where('role', '=', "superadmin")->first();

                if ($checkCardSaved == null) {

                    $customer = Customer::create([
                        'source' => $request->source['id'],
                        'email' => $email,
                    ]);
                    $customer_id = $customer->id;
                    $saveCard = new SaveCard();
                    $saveCard->user_id = $id;
                    $saveCard->card_token = $customer_id;
                    $saveCard->card_name = "Last4 " . $request->source['card']['last4'];
                    $saveCard->role = Auth::user()->roles()->first()->name;
                    $saveCard->description = json_encode($request->source['card']);
                    $saveCard->save();
                } else {
                    $customer_id = $checkCardSaved->card_token;
                }
            } else if (Auth::check() == false) {
                $customer_id = $request->source['id'];
            }
        }
        $GetItemData=Item::select(DB::raw('*,items_posteds.id as did,SUM(donated_vs_items.donated_amount) as sum_donated_amount'))
            ->join('items_posteds','items_posteds.item_id','=','items.id')
            ->leftJoin('donated_vs_items','donated_vs_items.item_id','=','items_posteds.id')
            ->where('items_posteds.id','=',$request->item_data['did'])
            ->where('donated_vs_items.item_id','=',$request->item_data['did'])
            ->groupBy('donated_vs_items.item_id')
            ->first();
        if($GetItemData == null){
            $getItemPrice = ItemsPosted::where('id','=',$request->item_data['did'])->first()->item_price;
        }else{
            $getItemPrice = $GetItemData->item_price;
        }
        if(($GetItemData == null ? 0 : $GetItemData->sum_donated_amount) + $saveAmount > $getItemPrice){
            return response("error");
        }else {
            ///Charging
            /// Get Payment Detials
            if($request->save_card === true) {
                $charge = Charge::create([
                    'amount' => round($amount * 100),
                    'currency' => 'usd',
                    'customer' => $customer_id,
                    'description' => "Item Name: '" . $getPaymentDetails->item_description . "', User Name: '" . $getPaymentDetails->email . "' ",
                ]);
                $transfer=Transfer::create([
                    'amount' => round($this->getAmountForPartner($amount,$typeAmount) * 100),
                    'currency' => 'usd',
                    "source_transaction" => $charge->id,
                    'destination' => $getConnectedAccountId,
                    'description' => "Item Name: '" . $getPaymentDetails->item_description . "', User Name: '" . $getPaymentDetails->email . "' ",
                ]);
            }else{
                //echo $amount * 100; die;
                $charge = Charge::create([
                    'amount' => round($amount * 100),
                    'currency' => 'usd',
                    'source' => $request->source['id'],
                    'description' => "Item Name: '" . $getPaymentDetails->item_description . "', User Name: '" . $getPaymentDetails->email . "' ",
                ]);

                $transfer=Transfer::create([
                    'amount' => round($this->getAmountForPartner($amount,$typeAmount) * 100),
                    'currency' => 'usd',
                    "source_transaction" => $charge->id,
                    'destination' => $getConnectedAccountId,
                    'description' => "Item Name: '" . $getPaymentDetails->item_description . "', User Name: '" . $getPaymentDetails->email . "' ",
                ]);

            }
        }
        if(Auth::user()->roles()->first()->name == "manager" || Auth::user()->roles()->first()->name == "staff"){
            $id=PartnerToStaff::where('staff_id','=',Auth::user()->id)->first()->partner_id;
        }else{
            $id=Auth::user()->id;
        }
        //Save Donation Record
        $saveDonation=new DonatedVsItems();
        $saveDonation->item_id=$request->item_data['did'];
        $saveDonation->donator_id=$id;
        $saveDonation->donated_amount=$saveAmount;
        $saveDonation->token=$charge->id;
        $saveDonation->save();

        //Update Status
        $GetItemData=Item::select(DB::raw('*,items_posteds.id as did,SUM(donated_vs_items.donated_amount) as sum_donated_amount'))
            ->join('items_posteds','items_posteds.item_id','=','items.id')
            ->leftJoin('donated_vs_items','donated_vs_items.item_id','=','items_posteds.id')
            ->where('items_posteds.id','=',$request->item_data['did'])
            ->where('donated_vs_items.item_id','=',$request->item_data['did'])
            ->groupBy('donated_vs_items.item_id')
            ->first();

        if($GetItemData->sum_donated_amount < $GetItemData->item_price){
            $status=2;
            $body=Auth::user()->first_name." has donated $".$amount." to help ".$GetItemData->customer_id;

            $title="New Donation";
            $color="text-blue";
            $type=json_encode(array("fas fa-donate $color",$getPaymentDetails->user_id,'/requests/donate/'.$request->item_data['did']));
        }else if($GetItemData->sum_donated_amount == $GetItemData->item_price){
            $status=0;
            $body=Auth::user()->first_name." has donated $".$amount." and donation amount is completed";
            $title="Donation Request Completed";
            $color="text-green";
            $type=json_encode(array("fas fa-donate $color",$getPaymentDetails->user_id,'/requests/donate/'.$request->item_data['did']));
            // $amount = ItemsPosted::where('id','=',$request->item_data['did'])->first()->item_price;
            // $amount = $this->getAmountForPartner($amount);

        }
        $title=$title;
        $body=$body;
        //$type=json_encode(array("fas fa-donate $color",$getPaymentDetails->user_id,'/partner/posted-items'));
        $token=Token::where('user_id','=',$getPaymentDetails->user_id)->pluck('token');
        $icon="/abc.png";
        $sendNotification=new UserController();
        $sendNotification->saveNotification($getPaymentDetails->user_id,$title,$body,$type,$icon);
        $sendNotification->sendPushNotifcations($title,$body,$type,$token,$icon);
        //Updating Status
        $updateinItemPosted=ItemsPosted::where('id','=',$request->item_data['did'])->update(['status' => $status]);


        if($charge->status == "succeeded"){
            return response("success");
        }else{
            return response("error");
        }
    }
    public function get_item_to_donate(Request $request){
        $post=Item::select(DB::raw('*,items_posteds.id as did'))
            ->join('items_posteds','items_posteds.item_id','=','items.id')
            ->where('items_posteds.id','=',$request->id)
            ->first();

        $checkFirst=DonatedVsItems::where('item_id','=',$post->did)->get();
        if(count($checkFirst) > 0){
            $getSumOfDonated = DonatedVsItems::select(DB::raw('SUM(donated_amount) as sum_donated_amount'))->where('item_id', '=', $request->id)->groupBy('item_id')->first()->sum_donated_amount;
        }else{
            $getSumOfDonated = 0;
        }
        $getPartnerName=User::where('id','=',$post->user_id)->first();
        $posts=array('business_name' => $getPartnerName->business_name,'business_address' => $getPartnerName->business_address,'number' => $getPartnerName->number,'partner_name' => $getPartnerName->first_name,'user_id' => $post->user_id,'item_description' => $post->item_description ,'customer_id' => $post->customer_id , 'item_name' =>  $post->item_name, 'item_price' => $post->item_price, 'sum_donated_amount' => $getSumOfDonated,'did' => $post->did,'status' => $post->status);

        return json_encode($posts);
    }
    public function save_bank(Request $request){
        //dd($request);
        $getStripeSecret=BankDetails::first()->SECRET_KEY;
        Stripe::setApiKey($getStripeSecret);
//        $checkCardSaved=SaveCard::where('user_id','=',Auth::user()->id)->first();
//        if($checkCardSaved == null){
        ///Getting Customer Card Cards
        if(Auth::user()->roles()->first()->name == "manager" || Auth::user()->roles()->first()->name == "staff"){
            $id=PartnerToStaff::where('staff_id','=',Auth::user()->id)->first()->partner_id;
        }else{
            $id=Auth::user()->id;
        }
        $getEmail=User::where('id','=',$id)->first()->email;
        $customer = Customer::create([
            'source' => $request->source['id'],
            'email' => $getEmail,
        ]);
        //Save Card
        $customer_id=$customer->id;
        $saveCard=new SaveCard();
        $saveCard->user_id=$id;
        $saveCard->card_token=$customer_id;
        $saveCard->card_name="Last4 ".$request->source['card']['last4']." ".$request->card_name;
        $saveCard->role=Auth::user()->roles()->first()->name;
        $saveCard->description=json_encode($request->source['card']);
        if($saveCard->save()){
            return response("success");
        }else{
            return response("error");
        }

//        }else{
//            return response("error");
//        }
    }
    public function getCardDetails(){
        if(Auth::user()->roles()->first()->name == "manager" || Auth::user()->roles()->first()->name == "staff"){
            $id=PartnerToStaff::where('staff_id','=',Auth::user()->id)->first()->partner_id;
        }else{
            $id=Auth::user()->id;
        }
        $checkCardSaved=SaveCard::where('user_id','=',$id)->get();
        //dd($checkCardSaved);
        foreach($checkCardSaved as $s){
            $decode=json_decode($s->description);
            // dd($decode);
            $data[]=array('card_name' => $s->card_name,'id' => $s->id,'brand' => $decode->brand,
                'month' => $decode->exp_month,'year' => $decode->exp_year,'last4' => $decode->last4);

        }
        return response(count($checkCardSaved) < 1 ? "none" : json_encode($data));
    }
    public function deleteCard(Request $request){
        $checkCardSaved=SaveCard::where('id','=',$request->id)->delete();
        if($checkCardSaved){
            return response("deleted");
        }else{
            return response("error");
        }
    }
    public function getPublicKey(){
        $getStripeSecret=BankDetails::first()->STRIPE_KEY;
        return response($getStripeSecret);
    }
    public function changeEnv($data = array()){
        if(count($data) > 0){

            // Read .env-file
            $env = file_get_contents(base_path() . '/.env');

            // Split string on every " " and write into array
            $env = preg_split('/\s+/', $env);;

            // Loop through given data
            foreach((array)$data as $key => $value){

                // Loop through .env-data
                foreach($env as $env_key => $env_value){

                    // Turn the value into an array and stop after the first split
                    // So it's not possible to split e.g. the App-Key by accident
                    $entry = explode("=", $env_value, 2);

                    // Check, if new key fits the actual .env-key
                    if($entry[0] == $key){
                        // If yes, overwrite it with the new one
                        $env[$env_key] = $key . "=" . $value;
                    } else {
                        // If not, keep the old one
                        $env[$env_key] = $env_value;
                    }
                }
            }

            // Turn the array back to an String
            $env = implode("\n", $env);

            // And overwrite the .env with the new data
            file_put_contents(base_path() . '/.env', $env);

            return true;

        } else {
            return false;
        }
    }
    public function getAmountForPartner($amount,$typeAmount){
        $getAmount= ((float)$amount * 2.9) /100;
        if($typeAmount == "full") {
            return $amount - $getAmount - 0.30;
        }else{
            return $amount - $getAmount - 0.30 - ($amount/10);
        }
    }
    public function getAmountForPartnerGET($amount){
        $getAmount= ((float)$amount * 2.9) /100;
        return $amount + $getAmount + 0.30;
    }
    public function showDescription(Request $request){
        $getStripeSecret=BankDetails::first()->SECRET_KEY;
//        }else{
//            $getStripeSecret=UserBankDetails::where('user_id','=',$request->item_data['user_id'])->first()->SECRET_KEY;
//        }
        $getConnectedAccountId=StripeUser::where('user_id','=',$request->item_data['user_id'])->first()->connect_id;
        $getPaymentDetails=ItemsPosted::join('users','users.id','=','items_posteds.user_id')->where('items_posteds.id','=',$request->item_data['did'])->first();
        Stripe::setApiKey($getStripeSecret);
        if($request->donatedAmount == "1"){
            $amount =(float) ItemsPosted::where('id','=',$request->item_data['did'])->first()->item_price;
            $saveAmount=$amount;
            $typeAmount="full";
        }
        if($request->donatedAmount == "2"){
            $GetItemData=Item::select(DB::raw('*,items_posteds.id as did,
            SUM(donated_vs_items.donated_amount) as sum_donated_amount'))
                ->join('items_posteds','items_posteds.item_id','=','items.id')
                ->leftJoin('donated_vs_items','donated_vs_items.item_id','=','items_posteds.id')
                ->where('items_posteds.id','=',$request->item_data['did'])
                ->where('donated_vs_items.item_id','=',$request->item_data['did'])
                ->groupBy('donated_vs_items.item_id')
                ->first();
            if($GetItemData == null){
                $amount =(float) ItemsPosted::where('id','=',$request->item_data['did'])->first()->item_price;
                $saveAmount=$amount;
                $typeAmount="full";
            }else{
                $amount =(float) $GetItemData->item_price - (float)$GetItemData->sum_donated_amount;
                $saveAmount=$amount;
                $amount = $this->getAmountForPartnerGET($amount);
                $typeAmount="remaining";
            }

        }
        if($request->donatedAmount == "3"){
            $amount = 1.00;
            $saveAmount=$amount;
            $amount = $this->getAmountForPartnerGET($amount);
            $typeAmount="remaining";

        }
        return json_encode(array('amount' => $saveAmount,'new_amount' => $amount,'type' => $typeAmount));
    }
}
