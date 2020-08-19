<?php

namespace App\Http\Controllers;

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
                return json_encode(array('status' => 401, 'found' => 'google'));
            }else{
                $checkEmailAndPassword = User::where('email','=',$request->email)->where('password','=',md5($request->password))->get();
                if(count($checkEmailAndPassword) > 0){
                    return json_encode(array('status' => 401, 'found' => 'yes'));
                }else{
                    return json_encode(array('status' => 404, 'found' => 'no'));
                }
            }
        }else{
            return json_encode(array('status' => 404, 'found' => 'no'));
        }
    }
}
