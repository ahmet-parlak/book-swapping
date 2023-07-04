<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class Home extends Controller
{
    function viewHome() { 
        
        if(Auth::check()){
            return redirect()->route("store");
        }

        return view('pages/home');
    }
}
