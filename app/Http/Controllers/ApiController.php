<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    //
    public function login(Request $request){
        $checkUser=User::where('email','=',$request->email)->get();
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
                    return json_encode(array( 'found' => 'yes','user' => $user , 'role' => $getRoleDetails->name , 'token' => User::where('id','=',$user->id)->first()->remember_token));
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
}
