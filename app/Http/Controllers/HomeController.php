<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(\Auth::user()->hasRole('admin')){
            return view('welcome');
        }

        else if(\Auth::user()->hasRole('enseignant')){
            return view('welcome'); 
        }

        else if(\Auth::user()->hasRole('etudiant')){
            return view('welcome'); 
        }

        else if(\Auth::user()->hasRole('visiteur')){
            return view('welcome');
        }
        else
        {
           $request->user()->authorizeRoles([
            'admin',
            'enseignant',
            'etudiant',
            'visiteur'
            ]);

            return view('welcome');
        }
        
    }
}
