<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Token;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {

            $user = Socialite::driver('google')->stateless()->user();
            //return $user->id;

            $finduser = User::where('google_id', $user->id)->first();

            if($finduser){

                Auth::login($finduser);

                 return redirect('/');

            }else{
                $finduser = User::where('email', $user->email)->get();
                if(count($finduser) > 0){
                    return redirect('/')->with('error','Email already present');
                }
                $newUser = User::create([
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'google_id'=> $user->id,
                    'type' => "google"
                ]);

                $getFunc=new UserController();

                $getSuperAdmins=User::select(DB::raw('*'))->join('role_user','users.id','=','role_user.user_id')
                    ->join('roles','roles.id','=','role_user.role_id')
                    ->where('roles.name','=','superadmin')->get();
                foreach($getSuperAdmins as $g) {
                    $color = "text-yellow";
                    $title = "New Partner Request";
                    $body = " Please review and approve Partner";
                    $type = json_encode(array("fas fa-user-plus $color", $g->user_id,'/admin/manage-user'));
                    $icon = "/abc";
                    $token = Token::select(DB::raw('*'))->join('role_user', 'tokens.user_id', '=', 'role_user.user_id')
                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                        ->where('roles.name', '=', 'superadmin')
                        ->where('role_user.user_id','=',$g->user_id)
                        ->pluck('token');

                    $getFunc->saveNotification($g->user_id, $title, $body, $type, $icon);
                    $getFunc->sendPushNotifcations($title, $body, $type, $token, $icon);
                }
                Auth::login($newUser);

                return redirect()->back();
            }

        } catch (Exception $e) {
            return redirect('auth/google');
        }
    }
}
