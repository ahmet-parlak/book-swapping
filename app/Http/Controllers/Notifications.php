<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification as NotificationModel;

class Notifications extends Controller
{
    public function markAsRead(Request $request)
    {
        $input = json_decode(array_keys($request->input())[0]);

        if ($input->request == "markAsRead") {

            $q = NotificationModel::where(['user_id' => Auth::user()->user_id, 'id' => $input->notification]);
            if ($q->count() == 1) {
                $q->delete();
                return response()->json([
                    "state" => "success",
                    "error" => ""
                ], 200);
            } else {
                return response()->json([
                    "state" => "error",
                    "error" => "error"
                ], 200);
            }
        } else {
            return response()->json([
                "state" => "error",
                "error" => "request error"
            ], 200);
        }
    }

    public function markAsReadAll(Request $request)
    {
        $input = json_decode(array_keys($request->input())[0]);

        if ($input->request == "markAsReadAll") {

            $q = NotificationModel::where(['user_id' => Auth::user()->user_id]);
            if ($q->count() > 0) {
                $q->delete();
                return response()->json([
                    "state" => "success",
                    "error" => ""
                ], 200);
            } else {
                return response()->json([
                    "state" => "error",
                    "error" => "error"
                ], 200);
            }
        } else {
            return response()->json([
                "state" => "error",
                "error" => "request error"
            ], 200);
        }
    }
}
