<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Logout extends Controller
{
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        print_r($request->input());
        if($request->input("password_changed")=="true"){
            return redirect()->route('login',["password_changed=true"]);
        }
        return redirect('/');
    }
}
