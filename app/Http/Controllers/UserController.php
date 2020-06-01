<?php

namespace App\Http\Controllers;

use App\BankDetails;
use App\DonatedVsItems;
use App\Http\Controllers\Auth\LoginController;
use App\Item;
use App\ItemsPosted;
use App\Mail\ChangeStatusNotification;
use App\Mail\PasswordReset;
use App\Mail\SendNotification;
use App\Notification;
use App\Offer;
use App\PartnerToStaff;
use App\Role;
use App\SaveCard;
use App\StripeUser;
use App\Token;
use App\User;
use App\UserBankDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Stripe\Exception\OAuth\OAuthErrorException;
use Stripe\OAuth;
use Stripe\Stripe;

class UserController extends Controller
{
    //
    public function forgot(){
        return view('auth.forgot');
    }
    public function send_verification_code(Request $request){
        $checkEmail=User::where('email','=',$request->email)->get();
        if(count($checkEmail) > 0){
            $checkIfCodeAlreadySent=User::where('email','=',$request->email)
                ->where('code','!=',null)->get();
            if(count($checkIfCodeAlreadySent) > 0){
                return back()->with('error',array('warning','Password reset link has already been sent on your '.$request->email.'.'));
            }else {
                $createCode = md5($request->email . "-" . date('dmyis'));
                $update = User::where('email', '=', $request->email)->update(['code' => $createCode]);
                $this->sendMailLink($request->email,$createCode);
                return back()->with('error', array('success', 'Password reset link has been sent on your ' . $request->email . '.'));
            }
        }else{
            return back()->with('error',array('danger','Email does not match to our record.'));
        }
    }
    public function sendMailLink($email,$code){
        $data=new \stdClass();
        $data->email=$email;
        $data->code=$code;
        //dd($data);
        Mail::to($email)->send(new PasswordReset($data));
    }
    public function password_reset(Request $request){
        $code=$request->code;
        $checkCode=User::where('code','=',$code)->get();
        if(count($checkCode) > 0){
            return view('auth.code')->with('code',$code);
        }else{
            abort(404);
        }
    }
    public function update_password_code(Request $request){
        $code=$request->code;
        $checkCode=User::where('code','=',$code)->get();
        if(count($checkCode) > 0){
            $update=User::where('code','=',$code)->update(['password' => md5($request->password)]);
            User::where('code','=',$code)->update(['code' => null]);
            return redirect('home');
        }else{
            return back()->with('error',array('danger','Link Expired.'));
        }
    }
    public function getRoles(Request $request)
    {
        if(Auth::user()->roles()->first()->name == "superadmin" && $request->key == "admin-add-user") {
            $roles = DB::table('roles')->whereNotIn('id',[5,6])->get();
        }else if((Auth::user()->roles()->first()->name == "partner" || Auth::user()->roles()->first()->name == "manager"  || Auth::user()->roles()->first()->name == "staff") && $request->key == "admin-add-user"){
            $roles = DB::table('roles')->whereNotIn('id',[1,2,3,4])->get();
        }else{
            $roles = array('name' => Auth::user()->roles()->first()->name);
        }
        return json_encode($roles);
    }
    public function getRole(){
        return response(Auth::user()->roles()->first()->id);
    }
    public function getRolesForEdit(Request $request)
    {
        if(Auth::user()->roles()->first()->name == "superadmin") {
            $roles = DB::table('roles')->whereNotIn('id',[5,6])->get();
        }else if(Auth::user()->roles()->first()->name == "partner" || Auth::user()->roles()->first()->name == "manager"  || Auth::user()->roles()->first()->name == "staff"){
            $roles = DB::table('roles')->whereNotIn('id',[1,2,3,4])->get();
        }
        else if($request->key == "user-edit-profile"){
            $roles = array('name' => Auth::user()->roles()->first()->name);
        }
        return json_encode($roles);
    }

    public function saveUser(Request $request)
    {
        $roleName=Auth::user()->roles->first()->name;
        $userId=Auth::user()->id;
        $checkIfAlreadyPresent=User::where('email','=',$request->email)->get();
        if(count($checkIfAlreadyPresent) > 0)
        {
            return response("duplicate");
        }else{
            $saveUser=new User();
            $saveUser->first_name=$request->first_name;
            $saveUser->last_name=$request->last_name;
            $saveUser->email=$request->email;
            $saveUser->password=md5($request->password);
            if($saveUser->save())
            {
                if($roleName == "partner"){
                    $userId=Auth::user()->id;
                }else if($roleName == "manager"){
                    $userId=PartnerToStaff::where('staff_id','=',Auth::user()->id)->first()->partner_id;
                }
                if($request->role == 5 || $request->role == 6){
                    if($roleName == "partner"){
                        $status=1;
                    }else if($roleName == "manager" && $request->role == 6){
                        $status=0;
                    }
                    else if($roleName == "manager" && $request->role == 5){
                        $status=1;
                    }
                    $getData=User::where('id','=',$userId)->first();
                    $updateUser=User::where('id','=',$saveUser->id)->update([
                        'status' => $status,
                        'business_name' => $getData->business_name,
                        'business_address' => $getData->business_address
                    ]);
                    $saveIntoPartners=new PartnerToStaff();
                    $saveIntoPartners->partner_id=$userId;
                    $saveIntoPartners->staff_id=$saveUser->id;
                    $saveIntoPartners->save();

                    $color="text-yellow";
                    $title="Manager added a staff";
                    $body=Auth::user()->first_name." added a staff";
                    $type=json_encode(array("fas fa-shield-alt $color",$userId,'/admin/manage-user'));
                    $icon="/abc";
                    $this->saveNotification($userId,$title,$body,$type,$icon);
                }
                $saveUser->attachRole($request->role);
                $this->sendMail($request->email,$request->first_name,$request->password,DB::table('roles')->where('id','=',$request->role)->first()->name); // MailableClass Name is called there
                return response("success");
            }else{
                return response("error");
            }
        }
    }
    public function logout(Request $request)
    {


        $request->session()->invalidate();
        return response('logout');
        //return redirect('/');
    }
    public function getAllUsers()
    {
        if(Auth::user()->roles()->first()->name == "superadmin") {
            $allUsers = User::select(DB::raw('*,users.id as user_id,roles.name as role_name'))->join('role_user', 'users.id', '=', 'role_user.user_id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->where('roles.id', '=', 1)
                ->orWhere('roles.id', '=', 2)
                ->get();
        }else if(Auth::user()->roles()->first()->name == "partner" || Auth::user()->roles()->first()->name == "manager"){
            if(Auth::user()->roles()->first()->name == "partner"){
                $userId=Auth::user()->id;
            }else if(Auth::user()->roles()->first()->name == "manager"){
                $userId=PartnerToStaff::where('staff_id','=',Auth::user()->id)->first()->partner_id;
            }
            $getIds=PartnerToStaff::where('partner_id','=',$userId)->pluck('staff_id');
            $allUsers = User::select(DB::raw('*,users.id as user_id,roles.name as role_name'))->join('role_user', 'users.id', '=', 'role_user.user_id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->whereIn('users.id', $getIds)
                ->get();

        }
        $data=null;
        foreach($allUsers as $u){
            $data[]=array('user_id' => $u->user_id,'first_name' => $u->first_name,'last_name' => $u->last_name,'email' => $u->email,'role_name' => $u->role_name,'status' => $u->status == "1" ? true : false );
        }
        return json_encode($data);
    }
    public function change_status(Request $request)
    {
        //echo $request->status;
        //$status= true;
        $status = 0;
        if($request->status == "true")
        {
            $status=1;
        }
        if($request->status === "false")
        {
            $status=0;
        }

        $updateUserTable=User::where('id','=',$request->user_id)->update(['status' => $status]);
        $getUser=User::where('id','=',$request->user_id)->first();
        $this->sendMailUpdateStatus($getUser->email,$getUser->first_name,$status);
    }
    public function getUsersWithThisID(Request $request)
    {
        $allUsers=User::select(DB::raw('*,users.id as user_id,roles.name as role_name'))
            ->join('role_user','users.id','=','role_user.user_id')
            ->join('roles','roles.id','=','role_user.role_id')
            ->where('users.id','=',$request->id)
            ->first();
        return json_encode($allUsers);
    }
    public function get_logined_user_information(){
        $allUsers=User::select(DB::raw('*,users.id as user_id,roles.name as role_name,bank_details.SECRET_KEY as admin_SECRET_KEY,
        user_bank_details.SECRET_KEY as user_SECRET_KEY'))
            ->join('role_user','users.id','=','role_user.user_id')
            ->join('roles','roles.id','=','role_user.role_id')
            ->leftJoin('save_cards','save_cards.user_id','=','users.id')
            ->leftJoin('bank_details','bank_details.user_id','=','users.id')
            ->leftJoin('user_bank_details','user_bank_details.user_id','=','users.id')
            ->where('users.id','=',Auth::user()->id)
            ->first();
        return json_encode($allUsers);
    }
    public function updateUser(Request $request){
        $checkIfAlreadyPresent=User::where('email','=',$request->email)->get();
        if(count($checkIfAlreadyPresent) > 1)
        {
            return response("duplicate");
        }else{
            if($request->password == null || $request->password == '')
            {
                $getPassword=User::where('id','=',$request->user_id)->first()->password;
                $pass="Not Changed";
            }else{
                $getPassword= md5($request->password );
                $pass=$request->password;
            }
            $update=User::where('id','=',$request->user_id)->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'password' => $getPassword,
                'email' => $request->email,
            ]);
            if($update)
            {
                $updateRole=DB::table('role_user')->where('user_id','=',$request->user_id)
                    ->update(['role_id' => $request->role_id]);
                $this->sendMail($request->email,$request->first_name,$pass,DB::table('roles')->where('id','=',$request->role_id)->first()->name); // MailableClass Name is called there

                return response("success");
            }else{
                return response("error");
            }
        }
    }
    public function save_bank_details(Request $request){
        if(Auth::user()->roles()->first()->name == "superadmin") {
            BankDetails::truncate();
            $saveNewBankDetails = new BankDetails();
            $saveNewBankDetails->user_id = Auth::user()->id;
            $saveNewBankDetails->STRIPE_KEY = $request->stripe_key;
            $saveNewBankDetails->SECRET_KEY = $request->stripe_secret;
            if ($saveNewBankDetails->save()) {

                $getFunc=new PaymentController();
                $getFunc->changeEnv(["STRIPE_PUBLIC_KEY" => $request->stripe_key]);
                return response("success");
            } else {
                return response("error");
            }
        }else{
            return response("warning");
        }
    }
    public function user_save_bank_details(Request $request){
        if(Auth::user()->roles()->first()->name == "manager" || Auth::user()->roles()->first()->name == "staff"){
            $id=PartnerToStaff::where('staff_id','=',Auth::user()->id)->first()->partner_id;
        }else{
            $id=Auth::user()->id;
        }
        $checkIfInformationAlreadyPresent=UserBankDetails::where('user_id','=',$id)->get();
        if(count($checkIfInformationAlreadyPresent) > 0){
            $saveDetails=UserBankDetails::where('user_id','=',$id)->update([
                'STRIPE_KEY' => $request->stripe_key,
                'SECRET_KEY' => $request->stripe_secret,
                'user_id' => Auth::user()->id
            ]);
        }else{
            $saveNewBankDetails = new UserBankDetails();
            $saveNewBankDetails->user_id = $id;
            $saveNewBankDetails->STRIPE_KEY = $request->stripe_key;
            $saveNewBankDetails->SECRET_KEY = $request->stripe_secret;
            $saveDetails=$saveNewBankDetails->save();
            if ($saveDetails) {
                return response("success");
            } else {
                return response("error");
            }
        }
    }
    public function getUserBankDetails(){
        $getuserData=UserBankDetails::where('user_id','=',Auth::user()->id)->first();
        if($getuserData === null){
            $getuserData=array('stripe_key' => '','stripe_secret' => '');
        }
        else{
            $getuserData=array('stripe_key' => $getuserData->STRIPE_KEY,'stripe_secret' => $getuserData->SECRET_KEY);
        }
        return json_encode($getuserData);
    }
    public function getAdminBankDetails(){
        $getuserData=BankDetails::first();
        if($getuserData === null){
            $getuserData=array('stripe_key' => '','stripe_secret' => '');
        }
        else{
            $getuserData=array('stripe_key' => $getuserData->STRIPE_KEY,'stripe_secret' => $getuserData->SECRET_KEY);
        }
        return json_encode($getuserData);
    }
    public function getAllDonors(){
        $allUsers=User::select(DB::raw('*,users.id as user_id,roles.name as role_name'))->join('role_user','users.id','=','role_user.user_id')
            ->join('roles','roles.id','=','role_user.role_id')
            ->where('roles.id','=',3)
            ->get();
        $data=null;
        foreach($allUsers as $u){
            $data[]=array('user_id' => $u->user_id,'first_name' => $u->first_name,'last_name' => $u->last_name,'email' => $u->email,'role_name' => $u->role_name,'status' => $u->status == "1" ? true : false );
        }
        return json_encode($data);
    }
    public function getAllPartners(){
        $allUsers=User::select(DB::raw('*,users.id as user_id,roles.name as role_name'))->join('role_user','users.id','=','role_user.user_id')
            ->join('roles','roles.id','=','role_user.role_id')
            ->where('roles.id','=',4)
            ->get();
        $data=null;
        foreach($allUsers as $u){
            $data[]=array('number' => $u->number,'business_name' => $u->business_name,'business_address' => $u->business_address,'user_id' => $u->user_id,'first_name' => $u->first_name,'last_name' => $u->last_name,'email' => $u->email,'role_name' => $u->role_name,'status' => $u->status == "1" ? true : false );
        }
        return json_encode($data);
    }
    public function getAllPartnersWithStatus(){
        $allUsers=User::select(DB::raw('*,users.id as user_id,roles.name as role_name'))
            ->join('role_user','users.id','=','role_user.user_id')
            ->join('roles','roles.id','=','role_user.role_id')
            ->where('roles.id','=',4)
            ->where('users.status','=',"0")
            ->get();
        $data=null;
        foreach($allUsers as $u){
            $data[]=array('number' => $u->number,'business_name' => $u->business_name,'business_address' => $u->business_address,'user_id' => $u->user_id,'first_name' => $u->first_name,'last_name' => $u->last_name,'email' => $u->email,'role_name' => $u->role_name,'status' => $u->status == "1" ? true : false );
        }
        return json_encode($data);
    }
    public function complete_profile(){
        return view("auth.complete_profile");
    }
    public function update_complete_profile(Request $request){
        $updateProfile=User::where('id','=',Auth::user()->id)->update(
            ['business_name' => $request->business_name,
                'business_address' => $request->business_address,
                'number' => $request->number]);
        return redirect("/");
    }
    public function update_role_profile(Request $request){
        $updateProfile=DB::table('role_user')->insert(["user_id" => Auth::user()->id,'role_id' => $request->role_id]);
        $updateUser=User::where('id','=',Auth::user()->id)->update([
            'status' => $request->role_id == "4" ? 0 : 1
        ]);
        return redirect("/");
    }
    public function pending_approval(){
        if(Auth::user()) {
            if (Auth::user()->status == "1") {
                return redirect('/');
            } else {
                return view('auth.pending_approval');
            }
        }else{
            return redirect('/');
        }
    }
    public function saveItem(Request $request){
        if(Auth::user()->roles()->first()->name == "manager" || Auth::user()->roles()->first()->name == "staff"){
            $id=PartnerToStaff::where('staff_id','=',Auth::user()->id)->first()->partner_id;
        }else{
            $id=Auth::user()->id;
        }
        $checkIfItemNameAlreadyPresent=Item::where('item_name','=',$request->item_name)
            ->where('user_id','=',$id)
            ->get();
        if(count($checkIfItemNameAlreadyPresent) > 0){
            return response("duplicate");
        }else{
            $saveItem= new Item();
            $saveItem->user_id=$id;
            $saveItem->item_name=$request->item_name;
            $saveItem->item_description=$request->item_description;
            $saveItem->item_price=$request->item_price;
            if($saveItem->save()){
                return response("success");
            }else{
                return response("error");
            }
        }
    }
    public function saveOffer(Request $request){
        if(Auth::user()->roles()->first()->name == "manager" || Auth::user()->roles()->first()->name == "staff"){
            $id=PartnerToStaff::where('staff_id','=',Auth::user()->id)->first()->partner_id;
        }else{
            $id=Auth::user()->id;
        }
        $saveItem= new Offer();
        $saveItem->user_id=$id;
        $saveItem->item_name=$request->item_name;
        $saveItem->item_description=$request->item_description;
        $saveItem->item_price=$request->item_price;
        if($saveItem->save()){
            return response("success");
        }else{
            return response("error");
        }
    }
    public function getAllItems(){
        if(Auth::user()->roles()->first()->name == "manager" || Auth::user()->roles()->first()->name == "staff"){
            $id=PartnerToStaff::where('staff_id','=',Auth::user()->id)->first()->partner_id;
        }else{
            $id=Auth::user()->id;
        }
        $items=Item::where('user_id','=',$id)->where('status','=','1')->get();
        return json_encode($items);
    }//
    public function getAllOffers(){
        if(Auth::user()->roles()->first()->name == "manager" || Auth::user()->roles()->first()->name == "staff"){
            $id=PartnerToStaff::where('staff_id','=',Auth::user()->id)->first()->partner_id;
        }else{
            $id=Auth::user()->id;
        }
        $items=Offer::where('user_id','=',$id)->where('status','=','1')->get();
        return json_encode($items);
    }//
    public function getAllOffersWithNames(){
        $items=Offer::select(DB::raw('*,offers.updated_at as offer_time'))->
        join('users','users.id','=','offers.user_id')
            ->where('offers.status','=','1')
            ->orderBy('offers.updated_at','ASC')
            ->get();
        return json_encode($items);
    }
    public function deleteItem(Request $request){
        $items=Item::where('id','=',$request->id)->update(['status' => '0']);
        if($items) {
            return response('success');
        }else{
            return response('error');
        }
    }
    public function deleteOffer(Request $request){
        $items=Offer::where('id','=',$request->id)->update(['status' => '0']);
        if($items) {
            return response('success');
        }else{
            return response('error');
        }
    }
    public function deleteRequest(Request $request){
        $checkFirst=DonatedVsItems::where('item_id','=',$request->id)->get();
        if(count($checkFirst) > 0){
            return response('notdeleted');
        }else {
            $items = ItemsPosted::where('id', '=', $request->id)->delete();
            if ($items) {
                return response('success');
            } else {
                return response('error');
            }
        }
    }
    public function getItemForthis(Request $request){
        $items=Item::where('id','=',$request->id)->first();
        return json_encode($items);
    }
    public function getOfferForthis(Request $request){
        $items=Offer::where('id','=',$request->id)->first();
        return json_encode($items);
    }
    public function updateItem(Request $request){
        if(Auth::user()->roles()->first()->name == "manager" || Auth::user()->roles()->first()->name == "staff"){
            $id=PartnerToStaff::where('staff_id','=',Auth::user()->id)->first()->partner_id;
        }else{
            $id=Auth::user()->id;
        }
        $checkIfItemNameAlreadyPresent=Item::where('item_name','=',$request->item_name)
            ->where('user_id','=',$id)
            ->get();
        if(count($checkIfItemNameAlreadyPresent) > 1){
            return response("duplicate");
        }else{
            $updateItem=Item::where('id','=',$request->id)->update([
                'item_name' => $request->item_name,
                'item_description' => $request->item_description,
                'item_price' => $request->item_price
            ]);
            if($updateItem){
                return response("success");
            }else{
                return response("error");
            }
        }
    }//updateOffer
    public function updateOffer(Request $request){
        if(Auth::user()->roles()->first()->name == "manager" || Auth::user()->roles()->first()->name == "staff"){
            $id=PartnerToStaff::where('staff_id','=',Auth::user()->id)->first()->partner_id;
        }else{
            $id=Auth::user()->id;
        }
        $updateItem=Offer::where('id','=',$request->id)->update([
            'item_name' => $request->item_name,
            'item_description' => $request->item_description,
            'item_price' => $request->item_price
        ]);
        if($updateItem){
            return response("success");
        }else{
            return response("error");
        }
    }
    public function postArequest(Request $request){
        //echo URL::asset('public/images/logo.png'); die;
        if(Auth::user()->roles()->first()->name == "manager" || Auth::user()->roles()->first()->name == "staff"){
            $id=PartnerToStaff::where('staff_id','=',Auth::user()->id)->first()->partner_id;
        }else{
            $id=Auth::user()->id;
        }
        $getData=User::where('id','=',$id)->first();
        $PostItem=new ItemsPosted();
        $PostItem->item_id=$request->item_id;
        $PostItem->user_id=$id;
        $PostItem->item_description=$request->item_description;
        $PostItem->item_price=$request->item_price;
        $PostItem->customer_id=$request->customer_name;
        if($PostItem->save()){
            $color="text-yellow";
            $title="Request for Donation";
            $body="Help ".$getData->business_name." serve ".$request->customer_name;
            $type=json_encode(array("fas fa-hand-holding-usd $color","donationPosted",'/requests/donate/'.$PostItem->id));
            $token=Token::select(DB::raw('*'))->join('role_user','tokens.user_id','=','role_user.user_id')
                ->join('roles','roles.id','=','role_user.role_id')
                ->where('roles.name','=','donor')->pluck('token');

            $icon="/abc";
            $this->saveNotification("donors",$title,$body,$type,$icon);
            $this->sendPushNotifcations($title,$body,$type,$token,$icon);
            return response("success");
        }else{
            return response("error");
        }
    }
    public function saveToken(Request $request){
        $checkFirstIfHaveToken=Token::where('user_id','=',Auth::user()->id)
            ->where('type','=',$request->type)->get();
        if(count($checkFirstIfHaveToken) > 0){
            $update=Token::where('user_id','=',Auth::user()->id)
                ->where('type','=',$request->type)->update(['token' => $request->token]);
        }else {
            $saveNotificationToken = new Token();
            $saveNotificationToken->user_id = Auth::user()->id;
            $saveNotificationToken->token = $request->token;
            $saveNotificationToken->type = $request->type;
            $saveNotificationToken->save();
        }
    }
    public function sendPushNotifcations($title,$body,$type,$token,$icon){
        if (!defined('SERVER_API_KEY')) define('SERVER_API_KEY', 'AIzaSyBe_D7NFNY4u6zTUNvMm06pIp9rtz2FCZA');
        //define('SERVER_API_KEY','AIzaSyBe_D7NFNY4u6zTUNvMm06pIp9rtz2FCZA'); //Legacy API KEY
        $tokens=$token;
        $headers=[
            'Authorization: Key='.SERVER_API_KEY,
            'Content-Type: Application/json'
        ];
        $typeForLink=json_decode($type);
        $msg=[
            'title' => $title,
            'body' => $body,
            'icon' => "/public/images/logo.png",
            'type' => $type,
            'click_action' => 'https://hungerbye.com/home#'.$typeForLink[2],

        ];
        $payload=[
            'registration_ids' => $tokens,
            'data' => $msg
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => $headers
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
//        if ($err) {
//            echo "cURL Error #:" . $err;
//        } else {
//            echo $response;
//        }
    }
    public function saveNotification($user,$title,$body,$type,$icon){
        if($user == "donor"){
         $all=Token::select(DB::raw('*,tokens.user_id as uid'))->join('role_user','tokens.user_id','=','role_user.user_id')
             ->join('roles','roles.id','=','role_user.role_id')
             ->where('roles.name','=','donor')->get();
         foreach($all as $u){
             $savePushNotification=new Notification();
             $savePushNotification->user_id=$u->uid;
             $savePushNotification->type=$type;
             $savePushNotification->title=$title;
             $savePushNotification->body=$body;
             $savePushNotification->icon=$icon;
             $savePushNotification->save();
         }
        }else {
            $savePushNotification = new Notification();
            $savePushNotification->user_id = $user;
            $savePushNotification->type = $type;
            $savePushNotification->title = $title;
            $savePushNotification->body = $body;
            $savePushNotification->icon = $icon;
            $savePushNotification->save();
        }
    }
    public function get_items_for_this_donor(){
        if(Auth::user()->roles()->first()->name == "manager" || Auth::user()->roles()->first()->name == "staff"){
            $id=PartnerToStaff::where('staff_id','=',Auth::user()->id)->first()->partner_id;
        }else{
            $id=Auth::user()->id;
        }
        $post=Item::select(DB::raw('*,items_posteds.id as did'))
            ->join('items_posteds','items_posteds.item_id','=','items.id')
            ->where('items_posteds.user_id','=',$id)
            ->orderBy('items_posteds.updated_at','DESC')
            ->get();
        $getPartnerName=User::where('id','=',$id)->first()->business_name;
        foreach($post as $p){
            $checkFirst=DonatedVsItems::where('item_id','=',$p->did)->get();
            if(count($checkFirst) > 0){
                $getSumOfDonated = DonatedVsItems::select(DB::raw('SUM(donated_vs_items.donated_amount) as sum_donated_amount'))->where('item_id', '=', $p->did)->groupBy('item_id')->first()->sum_donated_amount;
            }else{
                $getSumOfDonated = 0;
            }
            $posts[]=array('partner_name' => $getPartnerName,'item_description' => $p->item_description ,'customer_id' => $p->customer_id , 'item_name' =>  $p->item_name, 'item_price' => $p->item_price, 'sum_donated_amount' => $getSumOfDonated,'did' => $p->did,'status' => $p->status);
        }
        return json_encode($posts);

    }
    public function get_requests_to_show(){
        $post=Item::select(DB::raw('*,items_posteds.id as did'))
            ->join('items_posteds','items_posteds.item_id','=','items.id')
            ->orderBy('items_posteds.updated_at','DESC')
            ->get();
        foreach($post as $p){
            $checkFirst=DonatedVsItems::where('item_id','=',$p->did)->get();
            if(count($checkFirst) > 0){
                $getSumOfDonated = DonatedVsItems::select(DB::raw('*,SUM(donated_amount) as sum_donated_amount'))->where('item_id', '=', $p->did)->groupBy('item_id')->first()->sum_donated_amount;
            }else{
                $getSumOfDonated = 0;
            }
            $getPartnerName=User::where('id','=',$p->user_id)->first()->business_name;
            $posts[]=array('partner_name' => $getPartnerName,'item_description' => $p->item_description ,'customer_id' => $p->customer_id , 'item_name' =>  $p->item_name, 'item_price' => $p->item_price, 'sum_donated_amount' => $getSumOfDonated,'did' => $p->did,'status' => $p->status);
        }
        return json_encode($posts);
    }
    public function get_requests_to_show_opened(){
        if(Auth::user()->roles()->first()->name == "manager" || Auth::user()->roles()->first()->name == "staff"){
            $id=PartnerToStaff::where('staff_id','=',Auth::user()->id)->first()->partner_id;
        }else{
            $id=Auth::user()->id;
        }
        $post=Item::select(DB::raw('*,items_posteds.id as did'))
            ->join('items_posteds','items_posteds.item_id','=','items.id')
            ->orderBy('items_posteds.updated_at','DESC')
            ->orWhere('items_posteds.status', '=', '2')
            ->orWhere('items_posteds.status', '=', '1')
            //->where('items_posteds.status','=','1')
            ->get();
        foreach($post as $p){
            $checkFirst=DonatedVsItems::where('item_id','=',$p->did)->get();
            if(count($checkFirst) > 0){
                $getSumOfDonated = DonatedVsItems::select(DB::raw('*,SUM(donated_amount) as sum_donated_amount'))->where('item_id', '=', $p->did)->groupBy('item_id')->first()->sum_donated_amount;
            }else{
                $getSumOfDonated = 0;
            }
            $checkDonate=DonatedVsItems::where('donator_id','=',$id)->where('item_id', '=', $p->did)->get();
            $getPartnerName=User::where('id','=',$p->user_id)->first()->business_name;
            $posts[]=array('partner_name' =>$getPartnerName,'already_donated' => count($checkDonate) > 0 ? 'yes' : 'no','item_description' => $p->item_description ,'customer_id' => $p->customer_id , 'item_name' =>  $p->item_name, 'item_price' => $p->item_price, 'sum_donated_amount' => $getSumOfDonated,'did' => $p->did,'status' => $p->status);
        }
        return json_encode($posts);
    }
    public function get_requests_to_show_open_24(){
        $post=Item::select(DB::raw('*,items_posteds.id as did,items.user_id as i_u_id'))
            ->join('items_posteds','items_posteds.item_id','=','items.id')
            ->orderBy('items_posteds.updated_at','DESC')
            ->orWhere('items_posteds.status', '=', '2')
            ->orWhere('items_posteds.status', '=', '1')
            ->whereDate('items_posteds.created_at','<',date('Y-m-d'))
            ->get();
        foreach($post as $p){
            $checkFirst=DonatedVsItems::where('item_id','=',$p->did)->get();
            if(count($checkFirst) > 0){
                $getSumOfDonated = DonatedVsItems::select(DB::raw('*,SUM(donated_amount) as sum_donated_amount'))->where('item_id', '=', $p->did)->groupBy('item_id')->first()->sum_donated_amount;
            }else{
                $getSumOfDonated = 0;
            }
            $getPartnerName=User::where('id','=',$p->i_u_id)->first()->business_name;
            $posts[]=array('partner_name' =>$getPartnerName,'item_description' => $p->item_description ,'customer_id' => $p->customer_id , 'item_name' =>  $p->item_name, 'item_price' => $p->item_price, 'sum_donated_amount' => $getSumOfDonated,'did' => $p->did,'status' => $p->status);
        }
        return json_encode($posts);
    }
    public function get_my_requests_to_show_open_24(){
        if(Auth::user()->roles()->first()->name == "manager" || Auth::user()->roles()->first()->name == "staff"){
            $id=PartnerToStaff::where('staff_id','=',Auth::user()->id)->first()->partner_id;
        }else{
            $id=Auth::user()->id;
        }
        $post=Item::select(DB::raw('*,items_posteds.id as did,items.user_id as i_u_id'))
            ->join('items_posteds','items_posteds.item_id','=','items.id')
            ->orderBy('items_posteds.updated_at','DESC')
            ->orWhere('items_posteds.status', '=', '2')
            ->orWhere('items_posteds.status', '=', '1')
            ->where('items_posteds.user_id','=',$id)
            ->whereDate('items_posteds.created_at','<',date('Y-m-d'))
            ->get();
        foreach($post as $p){
            $checkFirst=DonatedVsItems::where('item_id','=',$p->did)->get();
            if(count($checkFirst) > 0){
                $getSumOfDonated = DonatedVsItems::select(DB::raw('*,SUM(donated_amount) as sum_donated_amount'))->where('item_id', '=', $p->did)->groupBy('item_id')->first()->sum_donated_amount;
            }else{
                $getSumOfDonated = 0;
            }
            $getPartnerName=User::where('id','=',$p->i_u_id)->first()->business_name;
            $posts[]=array('partner_name' =>$getPartnerName,'item_description' => $p->item_description ,'customer_id' => $p->customer_id , 'item_name' =>  $p->item_name, 'item_price' => $p->item_price, 'sum_donated_amount' => $getSumOfDonated,'did' => $p->did,'status' => $p->status);
        }
        return json_encode($posts);
    }
    public function get_requests_to_show_contributed(){
        if(Auth::user()->roles()->first()->name == "manager" || Auth::user()->roles()->first()->name == "staff"){
            $id=PartnerToStaff::where('staff_id','=',Auth::user()->id)->first()->partner_id;
        }else{
            $id=Auth::user()->id;
        }
        $post=Item::select(DB::raw('*,items_posteds.id as did'))
            ->join('items_posteds','items_posteds.item_id','=','items.id')
            ->join('donated_vs_items','items_posteds.id','=','donated_vs_items.item_id')
            ->orderBy('items_posteds.updated_at','DESC')
            ->where('donated_vs_items.donator_id','=',$id)
            ->get();
        foreach($post as $p){
            $checkFirst=DonatedVsItems::where('item_id','=',$p->did)->get();
            if(count($checkFirst) > 0){
                $getSumOfDonated = DonatedVsItems::select(DB::raw('*,SUM(donated_amount) as sum_donated_amount'))->where('item_id', '=', $p->did)->groupBy('item_id')->first()->sum_donated_amount;
            }else{
                $getSumOfDonated = 0;
            }
            $getDonatedAmountByDonor=DonatedVsItems::select(DB::raw('*,SUM(donated_amount) as sum_donated_amount'))->where('donator_id', '=', $id)->where('item_id', '=', $p->did)->groupBy('donator_id')->first()->sum_donated_amount;
            $getPartnerName=User::where('id','=',$p->user_id)->first()->business_name;
            $posts[]=array('donated_by_this' => $getDonatedAmountByDonor,'partner_name' => $getPartnerName,'item_description' => $p->item_description ,'customer_id' => $p->customer_id , 'item_name' =>  $p->item_name, 'item_price' => $p->item_price, 'sum_donated_amount' => $getSumOfDonated,'did' => $p->did,'status' => $p->status);
        }
        return json_encode($posts);
    }
    public function get_my_dashboard(){
        if(Auth::user()->roles()->first()->name == 'superadmin' || Auth::user()->roles()->first()->name == "editor") {
            $getDonors = User::select(DB::raw('*,users.id as user_id,roles.name as role_name'))->join('role_user', 'users.id', '=', 'role_user.user_id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->where('roles.id', '=', 3)
                ->get();
            $getPartners = User::select(DB::raw('*,users.id as user_id,roles.name as role_name'))->join('role_user', 'users.id', '=', 'role_user.user_id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->where('roles.id', '=', 4)
                ->get();
            $getStaff = User::select(DB::raw('*,users.id as user_id,roles.name as role_name'))->join('role_user', 'users.id', '=', 'role_user.user_id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->whereIn('roles.id', [1, 2])
                ->get();
            $getAllUsers = User::all();
            $getStipeForAdmin = BankDetails::first();
            $getCard = SaveCard::where('role', '=', 'superadmin')->first();
            /////-----------------------////
            $getAllRequest = ItemsPosted::all();
            $pending24 = ItemsPosted::orWhere('status', '=', '2')->orWhere('status', '=', '1')->whereDate('created_at','<',date('Y-m-d'))
                ->get();
            $getCompletedRequest = ItemsPosted::where('status', '=', '0')->get();
            $getPendingRequest = ItemsPosted::orWhere('status', '=', '2')->orWhere('status', '=', '1')->get();
            $checkFirst=DonatedVsItems::all();
            if(count($checkFirst) > 0) {
                $getDonation = DonatedVsItems::select(DB::raw('*,SUM(donated_vs_items.donated_amount) as sum_donated_amount'))
                    ->groupBy('item_id')
                    ->first()->sum_donated_amount;
            }else{
                $getDonation= 0;
            }
            $data=array("donors" => count($getDonors),"partners" => count($getPartners),"staff" => count($getStaff)
            ,"users" => count($getAllUsers),"all_requests" => count($getAllRequest),"completed_requests" => count($getCompletedRequest)
            ,"pending_requests" => count($getPendingRequest),"donation" => $getDonation,'stripe' => $getStipeForAdmin,
                'card' => $getCard,'pending24' => $pending24
            );
        }else if(Auth::user()->roles()->first()->name == 'partner' || Auth::user()->roles()->first()->name == "manager" || Auth::user()->roles()->first()->name == "staff"){
            if(Auth::user()->roles()->first()->name == "manager" || Auth::user()->roles()->first()->name == "staff"){
                $id=PartnerToStaff::where('staff_id','=',Auth::user()->id)->first()->partner_id;
            }else{
                $id=Auth::user()->id;
            }
            $getDonors = User::select(DB::raw('*,users.id as user_id,roles.name as role_name'))->join('role_user', 'users.id', '=', 'role_user.user_id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->where('roles.id', '=', 3)
                ->get();
            $getPartners = User::select(DB::raw('*,users.id as user_id,roles.name as role_name'))->join('role_user', 'users.id', '=', 'role_user.user_id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->where('roles.id', '=', 4)
                ->get();
            $getStaff = User::select(DB::raw('*,users.id as user_id,roles.name as role_name'))->join('role_user', 'users.id', '=', 'role_user.user_id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->whereIn('roles.id', [1, 2])
                ->get();
            $getAllUsers = User::all();
            $getStipeForAdmin = StripeUser::where('user_id', '=', $id)->get();
            if(count($getStipeForAdmin) > 0){
                $stripe='success';
            }else{
                $stripe='error';
            }
            $getCard = SaveCard::where('user_id', '=', $id)->first();
            /////-----------------------////
            $getAllRequest = ItemsPosted::where('user_id', '=', $id)->get();
            $pending24 = ItemsPosted::orWhere('status', '=', '2')->orWhere('status', '=', '1')->whereDate('created_at','<',date('Y-m-d'))
                ->get();
            $getCompletedRequest = ItemsPosted::where('status', '=', '0')->where('user_id', '=', $id)->get();
            $getPendingRequest = ItemsPosted::orWhere('status', '=', '2')->orWhere('status', '=', '1')->where('user_id', '=', $id)->get();
            $getItemsPostedByUser=ItemsPosted::where('user_id', '=', $id)->pluck('id');
            //dd($getItemsPostedByUser);
            $checkFirst=DonatedVsItems::whereIn('item_id',$getItemsPostedByUser)->get();
            if(count($checkFirst) > 0) {
                $getDonation = DonatedVsItems::select(DB::raw('*,SUM(donated_vs_items.donated_amount) as sum_donated_amount'))
                    ->whereIn('item_id',$getItemsPostedByUser)
                    ->groupBy('item_id')
                    ->get();
                $getDonations=0;
                foreach($getDonation as $g){
                    $getDonations += $g->sum_donated_amount;
                }
            }else{
                $getDonations= 0;
            }
            $data=array("donors" => count($getDonors),"partners" => count($getPartners),"staff" => count($getStaff)
            ,"users" => count($getAllUsers),"all_requests" => count($getAllRequest),"completed_requests" => count($getCompletedRequest)
            ,"pending_requests" => count($getPendingRequest),"donation" => $getDonations,'stripe' => $stripe,
                'card' => $getCard,'pending24' => $pending24
            );
        }else if(Auth::user()->roles()->first()->name == 'donor'){
            $checkFirst=DonatedVsItems::whereIn('donator_id',[Auth::user()->id])->get();
            $getPendingRequest = ItemsPosted::orWhere('status', '=', '2')->orWhere('status', '=', '1')->get();
            if(count($checkFirst) > 0) {
                $getDonation = DonatedVsItems::select(DB::raw('*,SUM(donated_vs_items.donated_amount) as sum_donated_amount'))
                    ->groupBy('item_id')
                    ->where('donator_id', '=', Auth::user()->id)
                    ->first()->sum_donated_amount;
            }else{
                $getDonation= 0;
            }
            $pending24 = ItemsPosted::where('status', '=', '0')->whereDate('created_at','<',date('Y-m-d'))
                ->get();

            $data=array("donation" => $getDonation,'pending24' => $pending24,"pending_requests" => count($getPendingRequest));
        }

        return json_encode($data);
    }
    public function sendMail($email,$username,$password,$role){
        $data=new \stdClass();
        $data->user_name=$username;
        $data->email=$email;
        $data->password=$password;
        $data->role=strtoupper($role);
        //dd($data);
        Mail::to($email)->send(new SendNotification($data));
    }
    public function sendMailUpdateStatus($email,$username,$status){
        $data=new \stdClass();
        $data->user_name=$username;
        $data->email=$email;
        $data->status=$status;
        //dd($data);
        Mail::to($email)->send(new ChangeStatusNotification($data));
    }
    public function delete_employee(Request $request){
        $delete=User::where('id','=',$request->id)->delete();
        if($delete){
            return response("deleted");
        }else{
            return response("notdeleted");
        }

    }
    public function getNotifications(){
        if(Auth::user()->roles()->first()->name == "manager" || Auth::user()->roles()->first()->name == "staff"){
            $id=PartnerToStaff::where('staff_id','=',Auth::user()->id)->first()->partner_id;
        }else{
            $id=Auth::user()->id;
        }
        $getNotifications=Notification::where('user_id','=',$id)->orderBy('created_at','DESC')->get();
        $getNotificationsCount=Notification::where('user_id','=',$id)->where('status','=','1')->orderBy('created_at','DESC')->get();
        if(count($getNotifications) > 0) {
            foreach ($getNotifications as $g) {
                $data[] = array('id' => $g->id,'count' => count($getNotificationsCount) > 0 ? count($getNotificationsCount) : 0, 'class' => json_decode($g->type), 'body' => $g->body);
            }
        }else{
            $data[] = array('id' => '0','count' => "0", 'class' => null, 'body' => null);
        }
        return json_encode($data);

    }
    public function markAsRead(){
        if(Auth::user()->roles()->first()->name == "manager" || Auth::user()->roles()->first()->name == "staff"){
            $id=PartnerToStaff::where('staff_id','=',Auth::user()->id)->first()->partner_id;
        }else{
            $id=Auth::user()->id;
        }
        Notification::where('user_id','=',$id)->update(['status' => 0]);
    }
    public function markAsReadThisNotif(Request $request){
        if(Auth::user()->roles()->first()->name == "manager" || Auth::user()->roles()->first()->name == "staff"){
            $id=PartnerToStaff::where('staff_id','=',Auth::user()->id)->first()->partner_id;
        }else{
            $id=Auth::user()->id;
        }
        Notification::where('id','=',$request->id)->update(['status' => 0]);
    }
    public function loginCheck(Request $request){
        //echo $request->email; die;
        $checkUser=User::where('email','=',$request->email)->where('google_id','!=',null)->get();
        if(count($checkUser) > 0){
            return back()->with('error','You are registered with Google.Please try Google Login');
        }else{
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                // Authentication passed...
                return redirect('home');
            }else{
                return back()->with('error','Incorrect username or password');
            }
        }
    }
    public function getAllPartnersWithRequest(){
        $allUsers=User::select(DB::raw('*,users.id as user_id,roles.name as role_name,count(items_posteds.user_id) as served'))->join('role_user','users.id','=','role_user.user_id')
            ->join('roles','roles.id','=','role_user.role_id')
            ->join('items_posteds','items_posteds.user_id','=','users.id')
            ->where('roles.id','=',4)
            ->groupBy('items_posteds.user_id')
            ->get();
        return json_encode($allUsers);
    }
    public function getPartnerWithServedRequests(Request $request){
        $allUsers=User::select(DB::raw('*,users.id as user_id,roles.name as role_name,count(items_posteds.user_id) as served'))->join('role_user','users.id','=','role_user.user_id')
            ->join('roles','roles.id','=','role_user.role_id')
            ->join('items_posteds','items_posteds.user_id','=','users.id')
            ->where('roles.id','=',4)
            ->where('users.id','=',$request->id)
            ->groupBy('items_posteds.user_id')
            ->first();
        return json_encode($allUsers);
    }
    public function getAllOffersForThisID(Request $request){

        $items=Offer::select(DB::raw('*,offers.updated_at as offer_time'))->
        join('users','users.id','=','offers.user_id')
            ->where('offers.status','=','1')
            ->where('offers.user_id','=',$request->id)
            ->orderBy('offers.updated_at','ASC')
            ->get();
        return json_encode($items);

    }
    public function stripeConnect(Request $request){
        $getStripeSecret=BankDetails::first()->SECRET_KEY;
        Stripe::setApiKey($getStripeSecret);
//        $abc=OAuth::deauthorize([
//            'client_id' => 'ca_H37jVr5IEZfQLmz4CeRyp5sY9gYhdb9u',
//            'stripe_user_id' => 'acct_1GXxXDHjezIi2txT',
//        ]);
//        dd($abc);
        $code=$request->code;
        try {
            $stripeResponse = OAuth::token([
                'grant_type' => 'authorization_code',
                'code' => $code,
            ]);
        } catch (OAuthErrorException $e) {
            dd($e);
        }

        $connect_id= $stripeResponse->stripe_user_id;
        $checkFirst=StripeUser::where('user_id','=',Auth::user()->id)->get();
        if(count($checkFirst) > 0){
            return back();
        }else{
            $save=new StripeUser();
            $save->user_id=Auth::user()->id;
            $save->connect_id=$connect_id;
            if($save->save()){
                return redirect('home');
            }
        }
    }
    public function getUserStripe(){
        if(Auth::user()->roles()->first()->name == "manager" || Auth::user()->roles()->first()->name == "staff"){
            $id=PartnerToStaff::where('staff_id','=',Auth::user()->id)->first()->partner_id;
        }else{
            $id=Auth::user()->id;
        }
        $getStripe=StripeUser::where('user_id','=',$id)->get();
        if(count($getStripe) > 0){
            return response('success');
        }else{
            return response('error');
        }

    }
}
