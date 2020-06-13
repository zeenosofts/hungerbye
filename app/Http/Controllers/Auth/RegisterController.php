<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\UserController;
use App\Token;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Role;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user= User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => md5($data['password']),
            'status' => $data['role_id'] == "4" ? 0 : 1
        ]);
        $getFunc=new UserController();

        $getSuperAdmins=User::select(DB::raw('*'))->join('role_user','users.id','=','role_user.user_id')
            ->join('roles','roles.id','=','role_user.role_id')
            ->where('roles.name','=','superadmin')->get();
        foreach($getSuperAdmins as $g) {
            $color = "text-yellow";
            $title = "New Partner Request";
            $body = " Please review and approve Partner";
            $type = json_encode(array("fas fa-user-plus $color", $g->user_id,'/admin/manage-partners'));
            $icon = "/abc";
            $token = Token::select(DB::raw('*'))->join('role_user', 'tokens.user_id', '=', 'role_user.user_id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->where('roles.name', '=', 'superadmin')
                ->where('role_user.user_id', '=', $g->user_id)
                ->pluck('token');

            $getFunc->saveNotification($g->user_id, $title, $body, $type, $icon);
            $getFunc->sendPushNotifcations($title, $body, $type, $token, $icon);
        }
        $newUser=$user->attachRole($data['role_id']);
        return $user;
    }
}
