<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function _registerOrLoginUser($data, $provider)
    {
        $user = User::where('email', $data->email)->first();
        if (!$user) {
            $user = new User();
            $user->firstname = $data->user['given_name'];
            $user->lastname = $data->user['family_name'];
            $user->email = $data->email;
            $user->provider = $provider;
            $user->provider_id = $data->id;
            $user->avatar = $data->avatar;
            $user->save();
        }
        Auth::login($user);
    }

    // Google Login
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    // Google callback  
    public function handleGoogleCallback()
    {

        $user = Socialite::driver('google')->stateless()->user();

        $this->_registerorLoginUser($user, 'google');
        return redirect()->route('home');
    }

    // Facebook Login
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->stateless()->redirect();
    }

    // facebook callback  
    public function handleFacebookCallback()
    {

        $user = Socialite::driver('facebook')->stateless()->user();

        $this->_registerorLoginUser($user, 'facebook');
        return redirect()->route('home');
    }

    // twitter Login
    public function redirectToTwitter()
    {
        return Socialite::driver('twitter')->stateless()->redirect();
    }

    // twitter callback  
    public function handleTwitterCallback()
    {

        $user = Socialite::driver('twitter')->stateless()->user();

        $this->_registerorLoginUser($user, 'twitter');
        return redirect()->route('home');
    }
}
