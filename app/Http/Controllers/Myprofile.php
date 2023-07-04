<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Models\Users;
use Intervention\Image\Facades\Image;

class Myprofile extends Controller
{
    public function viewMyProfile()
    {

        return view("pages/myprofile");
    }

    public function editProfile(Request $request)
    {
        $input = json_decode(array_keys($request->input())[0]);

        switch ($input->request) {
            case 'change_name':

                $pattern = "/^[a-zA-ZğüşöçıİĞÜŞÖÇ ]+$/u";
                $input->firstname = str_replace("_", " ", $input->firstname);
                if (preg_match($pattern, $input->firstname) && preg_match($pattern, $input->lastname) && strlen($input->firstname) > 2 && strlen($input->lastname) >= 2) {
                    Users::whereUser_id(Auth::id())->update([
                        "first_name" => ucfirst($input->firstname),
                        "last_name" => ucfirst($input->lastname),
                    ]);
                    return response()->json([
                        "state" => "success",
                        "error" => ""
                    ], 200);
                } else {
                    return response()->json([
                        "state" => "error",
                        "error" => $input->firstname
                    ], 200);
                }
                break;

            case 'change_password':
                if ($input->password == $input->confirm && strlen($input->password) > 5) {
                    Users::whereUser_id(Auth::id())->update([
                        "password" => Hash::make($input->password),
                    ]);
                    return response()->json([
                        "state" => "success",
                        "error" => ""
                    ], 200);
                } else {
                    return response()->json([
                        "state" => "error",
                        "error" => "validation error"
                    ], 200);
                }
                break;

            case 'change_region':
                $state = Users::whereUser_id(Auth::id())->update([
                    "city" => $input->city,
                    "district" => $input->district,
                ]);
                if ($state) {
                    return response()->json([
                        "state" => "success",
                        "error" => ""
                    ], 200);
                } else {
                    return response()->json([
                        "state" => "error",
                        "error" => "An error occurred during the update"
                    ], 200);
                }
                break;

            case 'change_phone':
                $state = Users::whereUser_id(Auth::id())->update([
                    "phone_number" => $input->phoneNumber,
                ]);
                if ($state) {
                    return response()->json([
                        "state" => "success",
                        "error" => ""
                    ], 200);
                } else {
                    return response()->json([
                        "state" => "error",
                        "error" => "An error occurred during the update"
                    ], 200);
                }
                break;


            default:
                return response()->json([
                    "state" => "error",
                    "error" => "request error"
                ], 200);
                break;
        }
    }

    public function uploadPP(Request $request)
    {
        //$name = $request->photo->getClientOriginalName();
        $ext = $request->photo->getClientOriginalExtension();

        if ($ext != "png" && $ext != "jpg" && $ext != "jpeg" && $ext != "gif") {
            return redirect()->route("myprofile", ["extensionError=true"]);
        }


        if ($request->photo->getSize() > 1600000) {
            return redirect()->route("myprofile", ["oversize=true"]);
        }

        $oldPhoto = Auth::user()->user_photo;
        if ((explode("\\", $oldPhoto)[3]) != "default.png") {
            File::delete($oldPhoto);
        }

        $photoName = md5(Auth::id()) . "." . $ext;
        //$upload=$request->photo->move(public_path('media/profiles/photos'),$photoName);
        $upload = Image::make($request->photo)->resize(200, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save('media/profiles/photos/' . $photoName); //->resize(200,200)
        $state = Users::whereUser_id(Auth::id())->update([
            "user_photo" => 'media\\profiles\\photos\\' . $photoName,
        ]);

        if ($upload && $state) {
            return redirect()->route("myprofile", ["ppUpdate=success"]);
        } else {
            return redirect()->route("myprofile", ["ppUpdate=error"]);
        }
    }
}
