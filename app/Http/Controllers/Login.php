<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Users;
use App\Models\NotificationChannels;

class Login extends Controller
{
    public function viewLogin()
    {
        if (Auth::check()) {
            return redirect()->route("store");
        }
        return view("pages/login");
    }


    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ], [
            'email.required' => 'Lütfen e-posta adresinizi girin.',
            'password.required' => 'Lütfen şifrenizi girin.',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $value = NotificationChannels::whereUser(Auth::user()->user_id)->first()->channel;
            $request->session()->put('notification_number', $value);

            if (!Auth::user()->last_login) { //if first time login (Last login deafult null)
                Users::whereUser_id(Auth::id())->update([
                    "last_login" => date('Y-m-d H:i:s'),

                ]);
                return redirect()->intended('info');
            } else {
                Users::whereUser_id(Auth::id())->update([
                    "last_login" => date('Y-m-d H:i:s'),

                ]);
                return redirect()->intended('store');
            }
        }

        return back()->withErrors([
            'email' => 'E-posta ya da şifre hatalı.',
        ])->onlyInput('email');
    }
}
