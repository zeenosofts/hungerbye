<?php

namespace App\Http\Controllers;

use App\DonatedVsItems;
use App\Item;
use App\ItemsPosted;
use App\Offer;
use App\PartnerToStaff;
use App\Role;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    //
    public function login(Request $request){
        $completed = 'yes';
        $checkUser=User::where('email','=',$request->email)->where('status','=',1)->get();
        if(count($checkUser) > 0){
            $checkUserIfGoogle=User::where('email','=',$request->email)->where('google_id','!=',null)->get();
            if(count($checkUserIfGoogle) > 0){
                return json_encode(array( 'found' => 'google'));
            }else{
                $checkEmailAndPassword = User::where('email','=',$request->email)->where('password','=',md5($request->password))->get();
                if(count($checkEmailAndPassword) > 0){
                    $user = User::where('email','=',$request->email)->where('password','=',md5($request->password))->first();
                    $getRoleDetails = Role::join('role_user','roles.id','=','role_user.role_id')->where('role_user.user_id','=',$user->id)->first();
                    $updateToken = User::where('id','=',$user->id)->update(['remember_token' => md5(time())]);
                    //return response($getRoleDetails);
                    if($getRoleDetails->name == 'partner'){
                        if($user->business_name == null || $user->business_name == ''){
                            $completed = 'no';
                        }
                    }else{
                        $completed = 'yes';
                    }
                    return json_encode(array( 'found' => 'yes','completed' => $completed,'user' => $user , 'role' => $getRoleDetails->name , 'token' => User::where('id','=',$user->id)->first()->remember_token));
                }else{
                    return json_encode(array( 'found' => 'no'));
                }
            }
        }else{
            return json_encode(array( 'found' => 'no'));
        }
    }
    public function registerDonor(Request $request){
        $checkUser=User::where('email','=',$request->email)->get();
        if(count($checkUser) > 0){
            return json_encode(array( 'found' => 'already'));
        }else{
            $user= User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => md5($request->password),
                'status' => 1
            ]);
            $user->attachRole(3);
            return $this->getUserDetails($request->email,$request->password);
        }
    }
    public function getUserDetails($email,$password){
        $user = User::where('email','=',$email)->where('password','=',md5($password))->first();
        $getRoleDetails = Role::join('role_user','roles.id','=','role_user.role_id')->where('role_user.user_id','=',$user->id)->first();
        $updateToken = User::where('id','=',$user->id)->update(['remember_token' => md5(time())]);
        return json_encode(array( 'found' => 'yes','user' => $user , 'role' => $getRoleDetails->name , 'token' => User::where('id','=',$user->id)->first()->remember_token));
    }
    public function findToken(Request $request){
        $user = User::where('remember_token','=',$request->remember_token)->get();
        if(count($user) > 0){
            return json_encode(array( 'found' => 'yes'));
        }else{
            return json_encode(array( 'found' => 'no'));
        }
    }
    public function register(Request $request){
        //return response($request->all());
        try {
            $checkIfEmailAlreadyPresent = User::where('email', '=', $request->email)->get();
            if (count($checkIfEmailAlreadyPresent) > 0) {
                return response('Email already present');
            } else {
                $user = new User();
                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->email = $request->email;
                $user->password = md5($request->password);
                $user->status = $request->role_id == "4" ? 0 : 1;
                $user->save();
                $newUser = $user->attachRole($request->role_id);
                return response('Account created successfully');
            }
        }catch (QueryException $e){
            return $e;
        }

    }
    public function send_verification_code(Request $request){
        $checkEmail=User::where('email','=',$request->email)->get();
        if(count($checkEmail) > 0){
            $checkIfCodeAlreadySent=User::where('email','=',$request->email)
                ->where('code','!=',null)->get();
            if(count($checkIfCodeAlreadySent) > 0){
                return response('Password reset link has already been sent on your '.$request->email.'.');
            }else {
                $createCode = md5($request->email . "-" . date('dmyis'));
                $update = User::where('email', '=', $request->email)->update(['code' => $createCode]);
                $user = new UserController();
                $user->sendMailLink($request->email,$createCode);
                return response('Password reset link has been sent on your');
            }
        }else{
            return response('Email does not match to our record');
        }
    }
    public function update_complete_profile(Request $request){
        $updateProfile=User::where('id','=',$request->user_id)->update(
            ['business_name' => $request->business_name,
                'business_address' => $request->business_address,
                'number' => $request->number]);
        return response('Profile Completed Successfully');
    }
    public function CheckROle($user_id){
        $checkRole = DB::table('role_user')->select('*')->where('user_id',$user_id)->first();
        if($checkRole->role_id == 5 || $checkRole->role_id == 6){
            $id=PartnerToStaff::where('staff_id','=',Auth::user()->id)->first()->partner_id;
        }else{
            $id = $user_id;
        }
        return $id;
    }
    public function saveItem(Request $request){
        $id= $this->CheckROle($request->user_id);
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
        $id= $this->CheckROle($request->user_id);
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
    public function getItems(Request $request){
        $id= $this->CheckROle($request->user_id);
        $getItems = Item::where('user_id','=',$id)->where('status',1)->get();
        return json_encode($getItems);
    }
    public function getOffers(Request $request){
        $id= $this->CheckROle($request->user_id);
        $getItems = Offer::where('user_id','=',$id)->where('status',1)->get();
        return json_encode($getItems);
    }
    public function PostedRequests(Request $request){
        $id= $this->CheckROle($request->user_id);
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

    public function postARequest(Request $request){
        $id= $this->CheckROle($request->user_id);
        $getItems = Item::where('id','=',$request->item_id)->first();
        $getData=User::where('id','=',$id)->first();
        $PostItem=new ItemsPosted();
        $PostItem->item_id=$request->item_id;
        $PostItem->user_id=$id;
        $PostItem->item_description=$getItems->item_description;
        $PostItem->item_price=$getItems->item_price;
        $PostItem->customer_id=$request->customer_name;
        $PostItem->save();
        return response('success');
    }
    public function deleteItem(Request $request){
        $items=Item::where('id','=',$request->id)->update(['status' => '0']);
       // return response($request->id);
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
    //public function
}
