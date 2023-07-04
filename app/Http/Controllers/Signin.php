<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Users;
use App\Models\NotificationChannels;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class Signin extends Controller
{

    public function viewSignin()
    {
        if (Auth::check()) {
            return redirect()->route("store");
        }
        return view("pages/signin");
    }

    public function register(Request $request)
    {

        $request->validate([
            "email" => "required|email:rfc,dns|unique:users,email",
            "password" => "required|min:6|max:100",
            "firstName" => "required|min:3|max:50|regex:/^[a-zA-ZğüşöçıİĞÜŞÖÇ ]+$/u",
            "lastName" => "required|min:2|max:50|regex:/^[a-zA-ZğüşöçıİĞÜŞÖÇ]+$/u",
            "city" => "required|min:3|max:50|regex:/^[a-zA-ZğüşöçıİĞÜŞÖÇ]+$/u",
            "district" => "required|min:3|max:50|regex:/^[a-zA-ZğüşöçıİĞÜŞÖÇ]+$/u",
        ], [
            'email.unique' => 'Bu e-posta adresi sistemde kayıtlı!',
            'email.required' => 'Lütfen bir e-posta adresi girin.',
            'email.email' => 'Lütfen geçerli bir e-posta adresi girin.',
        ]);

        $user = Users::create([
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "first_name" => ucfirst($request->firstName),
            "last_name" => ucfirst($request->lastName),
            "city" => $request->city,
            "district" => $request->district,
        ]);

        while (true) {
            $channel = rand(1000, 9999) . rand(1000, 9999) . rand(1000, 9999);
            if (NotificationChannels::whereChannel($channel)->count() == 0) {
                break;
            }
        }
        NotificationChannels::create([
            "user" => $user->user_id,
            "channel" => $channel,
        ]);




        return redirect()->route("login", ['pageState' => 'Registration Successful']);
    }
}
