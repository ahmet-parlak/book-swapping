<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\support\Facades\DB;
use App\Models\Contacts;
use App\Models\Message;
use App\Models\Users;
use App\Models\NotificationChannels;
use App\Models\Notification as NotificationModel;
use App\Events\Message as MessageEvent;
use App\Events\SendNotification;
use Notification;

class Messages extends Controller
{
    public function myMessages()
    {
        $contacts = Contacts::where('user', '=', Auth::user()->user_id);
        $contactCount = $contacts->count();
        if ($contactCount == 0) {
            $contacts = [];
        } else {
            $contactNums = [];
            foreach ($contacts->get() as $contact) {
                if (Message::whereContact_number($contact->contact_number)->count() != 0) { //contact have message
                    array_push($contactNums, $contact->contact_number);
                }
            }

            $contactCount = count($contactNums);
            if ($contactCount > 0) {
                $contacts = Contacts::where('user_id', '!=', Auth::user()->user_id)->where(function ($q) use ($contactNums) {
                    foreach ($contactNums as $contactNum) {
                        $q->orWhere('contact_number', '=', $contactNum);
                    }
                })->join('users', 'users.user_id', '=', 'contacts.user')->orderBy('contacts.updated_at', 'desc')->get();
            }
        }

        return view("pages/messages", compact(['contactCount', 'contacts']));
    }


    public function touser($userId)
    {
        if ($userId == Auth::user()->user_id || Users::whereUser_id($userId)->count() == 0) {
            return redirect()->route('messages');
        }

        $user = Users::whereUser_id($userId)->first();
        $contactNum = 0;
        $userContacts = Contacts::whereUser(Auth::user()->user_id)->get();
        foreach ($userContacts as $contact) {
            if (Contacts::where(['user' => $userId, 'contact_number' => $contact->contact_number])->count() == 1) {
                $contactNum = $contact->contact_number;
                break;
            }
        }

        if ($contactNum == 0) {
            $contactNum = generateContactNumber();
            createContact([Auth::user()->user_id, $userId], $contactNum);
        }

        $messages = Message::whereContact_number($contactNum)->get();

        //Delete Message Notifications
        NotificationModel::where(['user_id' => Auth::user()->user_id, 'sender' => $userId, 'type' => 'message'])->delete();

        return view('pages/message', compact('user', 'messages', 'contactNum'));
    }

    public function sendMessage(Request $request)
    {

        $arr = explode("/", $_SERVER['HTTP_REFERER']);
        if ($arr[count($arr) - 1] != $request->input('user')) {
            return response()->json([
                "state" => "error",
                "user" =>  "",
                "message" => "",
                "error" => "discord",
            ], 200);
        }
        $contactNum = 0;
        $userContacts = Contacts::whereUser(Auth::user()->user_id)->get();
        foreach ($userContacts as $contact) {
            if (Contacts::where(['user' => $request->input('user'), 'contact_number' => $contact->contact_number])->count() == 1) {
                $contactNum = $contact->contact_number;
            }
        }

        $msg = createMessage(Auth::user()->user_id, $request->input('user'), $request->input('message'), $contactNum);

        Contacts::whereContact_number($contactNum)->update([]); //last messsage contact

        broadcast(new MessageEvent($msg->message, $msg->sender, $msg->updated_at, $contactNum))->toOthers();


        /*********************  Send Notification *********************/
        if (NotificationModel::where(["user_id" => $request->input('user'), "sender" => Auth::user()->user_id])->count() == 0) {

            $created = NotificationModel::create([
                "user_id" => $request->input('user'),
                "sender" => Auth::user()->user_id,
                "message" => "kullancısı size mesaj göderdi",
                "link" => route('toUser', Auth::user()->user_id),
                "type" => "message",
            ]);
            $notification_channel = NotificationChannels::whereUser($request->input('user'))->first()->channel;
            $sender = Auth::user()->first_name . substr(Auth::user()->last_name, 0, 1) . ".";
            $sender_photo = Auth::user()->user_photo;
            $link = route('toUser', Auth::user()->user_id);
            broadcast(new SendNotification("kullanıcısı size mesaj göderdi", $sender, $sender_photo, $link, $notification_channel, $created->id))->toOthers();
            
        }

        return [
            "success" => true,
            "user" => $request->input('user'),
            "message" => $msg->message,
            "date" => $msg->updated_at,
        ];
    }
}


function generateContactNumber()
{
    while (true) {
        $cn = rand(1000, 9999) . rand(1000, 9999) . rand(1000, 9999);
        if (Contacts::where(["contact_number" => $cn])->count() == 0) break;
    }

    return $cn;
}

function createMessage($sender, $receiver, $message, $contactNum)
{
    return Message::create([
        "sender" => $sender,
        "receiver" => $receiver,
        "message" => $message,
        "contact_number" => $contactNum
    ]);
}

function createContact($users, $contactNum)
{
    foreach ($users as $user) {
        Contacts::create([
            "user" => $user,
            "contact_number" => $contactNum
        ]);
    }
}
