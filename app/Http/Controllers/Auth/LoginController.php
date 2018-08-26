<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    //protected $redirectTo = '/home';
    
    public function redirectTo() 
    {
        if(\Auth::user()->hasRole('admin')){
            return ('welcome');
        }

        else if(\Auth::user()->hasRole('enseignant')){
            return ('welcome');
        }

        else if(\Auth::user()->hasRole('etudiant')){
            return ('welcome');
        }

        else if(\Auth::user()->hasRole('visiteur')){
            return ('welcome');
        }
        else
        {
            return route('welcome'); 
        }
        
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
}
