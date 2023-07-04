<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\support\Facades\DB;
use App\Models\Users;
use App\Models\Trades;
use App\Models\UsersTrades;
use App\Models\BooksTrades;
use App\Models\Books;
use App\Models\Bookshelf;
use App\Models\Notification as NotificationModel;
use App\Models\NotificationChannels;
use App\Events\SendNotification;
use function PHPUnit\Framework\isNull;

class Trade extends Controller
{
    function myTrades()
    {


        $trades = DB::table('trades')
            ->join('users_trades as ut', 'trades.trade_number', '=', 'ut.trade_number')
            ->where(['ut.user_id' => Auth::user()->user_id])->select(["ut.*", "trades.state as trade_state"])->orderBy('trade_state')->orderByDesc('trades.updated_at')->paginate(5);
        $users = [];
        foreach ($trades as $trade) {
            $user = DB::table('users_trades')->join('users', function ($join) use ($trade) {
                $join->on("users_trades.user_id", "=", "users.user_id");
                $join->where("users_trades.trade_number", "=", $trade->trade_number);
                $join->where("users.user_id", "!=", Auth::user()->user_id);
            })->select("users.*")->first();

            array_push($users, $user);
        }

        /* URL Page Param. Manipulate Control */
        if ($trades->currentPage() > 1 && $trades->count() == 0) {
            return redirect()->route('mytrades');
        }

        $users = json_encode($users);
        return view('pages/mytrades', compact('trades', 'users'));
    }

    function tradeView(Request $request)
    {
        //If user param. exist & user exist & user is not auth. user
        if ($request->get('user') && Users::find($request->get('user')) && $request->get('user') != Auth::user()->user_id) {

            $user = Users::find($request->get('user'));

            $authUserTrades = UsersTrades::whereUser_id(Auth::user()->user_id)->get();

            foreach ($authUserTrades as $trade) {
                $activeTrade = UsersTrades::whereUser_id($user->user_id)->join("trades", "trades.trade_number", "=", "users_trades.trade_number")->where("users_trades.trade_number", "=", $trade->trade_number)->where(function ($q) {
                    $q->orwhere('trades.state', '=', 'active');
                    $q->orwhere('trades.state', '=', 'accepted');
                })->count();
                if ($activeTrade) {
                    return redirect()->route('mytrades', ['state' => 'active trade exist']);
                }
            }

            $userBooks = DB::table("bookshelf")
                ->join("books", function ($join) {
                    $join->on("books.book_id", "=", "bookshelf.book_id");
                    $join->where("books.state", "=", "active");
                })
                ->where("bookshelf.user_id", "=", $user->user_id)->where("bookshelf.state", "=", "active")
                ->select("books.*")->paginate(5)->withPath('/user?user=' . $user->user_id);

            $books = DB::table("bookshelf")
                ->join("books", function ($join) {
                    $join->on("books.book_id", "=", "bookshelf.book_id");
                    $join->where("books.state", "=", "active");
                })
                ->where("bookshelf.user_id", "=", Auth::user()->user_id)->where("bookshelf.state", "=", "active")
                ->select("books.*")->paginate(5)->withPath('/user?user=' . $user->user_id);


            if ($books->count() == 0) {
                $state = "bookshelf empty";
                return redirect()->route('store', compact('state'));
            }

            if ($userBooks->count()) {
                return view('pages/trade', compact(['user', 'userBooks', 'books']));
            }
        }

        return redirect()->route('home');
    }

    function openTrade($trade_number)
    {
        if (UsersTrades::where(['users_trades.trade_number' => $trade_number, 'users_trades.user_id' => Auth::user()->user_id])->count()) {
            //Trade Bilgileri
            $trade = Trades::whereTrade_number($trade_number)->first();

            //Kullanıcı Bilgileri
            $user = UsersTrades::whereTrade_number($trade_number)->where('users_trades.user_id', '!=', Auth::user()->user_id);
            $user = $user->join('users', 'users.user_id', '=', 'users_trades.user_id')->first();

            $authTradeState = UsersTrades::whereTrade_number($trade_number)
                ->where('users_trades.user_id', '=', Auth::user()->user_id)->first();
            $userTradeState = UsersTrades::whereTrade_number($trade_number)
                ->where('users_trades.user_id', '!=', Auth::user()->user_id)->first();

            /* #####[BOOKS]#####[ */
            if ($trade->state != "active") {
                /* [TRADE IS NOT ACTIVE] */
                $userBooks = DB::table("books_trades")
                    ->join("books", function ($join) {
                        $join->on("books.book_id", "=", "books_trades.book_id");
                    })
                    ->whereTrade_number($trade_number)
                    ->where("books_trades.user_id", "=", $user->user_id)
                    ->select(["books.*"])->get();
                $books = DB::table("books_trades")
                    ->join("books", function ($join) {
                        $join->on("books.book_id", "=", "books_trades.book_id");
                    })
                    ->whereTrade_number($trade_number)
                    ->where("books_trades.user_id", "=", Auth::user()->user_id)
                    ->select(["books.*"])->get();

                if ($books && $userBooks) {
                    //Delete Trade Notifications
                    NotificationModel::where(['user_id' => Auth::user()->user_id, 'sender' => $user->user_id, 'type' => 'trade'])->delete();
                    return view("pages/trade", compact(['trade', 'user', 'authTradeState', 'userTradeState', 'userBooks', 'books']));
                }
            }

            /* [TRADE IS ACTIVE] */

            $userBooks = DB::table('bookshelf')->join('books', function ($join) use ($user) {
                $join->on('books.book_id', '=', 'bookshelf.book_id');
                $join->where('bookshelf.user_id', '=', $user->user_id);
            })->leftJoin('books_trades', function ($join) use ($user, $trade_number) {
                $join->on('books_trades.book_id', '=', 'books.book_id');
                $join->where('books_trades.user_id', '=', $user->user_id);
                $join->where('books_trades.trade_number', '=', $trade_number);
            })->select(['books.*', 'books_trades.book_id as trades_book_id', 'bookshelf.state as bookshelf_state'])->get();

            $books = DB::table('bookshelf')->join('books', function ($join) {
                $join->on('books.book_id', '=', 'bookshelf.book_id');
                $join->where('bookshelf.user_id', '=', Auth::user()->user_id);
            })->leftJoin('books_trades', function ($join) use ($trade_number) {
                $join->on('books_trades.book_id', '=', 'books.book_id');
                $join->where('books_trades.user_id', '=', Auth::user()->user_id);
                $join->where('books_trades.trade_number', '=', $trade_number);
            })->select(['books.*', 'books_trades.book_id as trades_book_id', 'bookshelf.state as bookshelf_state'])->get();

            //Delete Trade Notifications
            NotificationModel::where(['user_id' => Auth::user()->user_id, 'sender' => $user->user_id, 'type' => 'trade'])->delete();

            return view("pages/trade", compact(['trade', 'user', 'authTradeState', 'userTradeState', 'userBooks', 'books']));
        } else {
            return redirect()->back();
        }
    }

    function tradeOffer(Request $request)
    {
        $request->validate([
            "user" => "required|numeric|exists:users,user_id",
        ]);
        //At least one book selected
        if ($request->give && $request->take) {

            //Given Books Control
            if ($request->give) {
                $control = 1;
                foreach ($request->give as $book_id) {
                    if (!Books::whereBook_id($book_id)->count() && Bookshelf::where(["user_id" => Auth::user()->user_id, "book_id" => $book_id, "state" => "active"])) {
                        $control = 0;
                    }
                }
                if (!$control) {
                    return redirect()->back();
                }
            }
            //Taken Books Control
            if ($request->take) {
                $control = 1;
                foreach ($request->take as $book_id) {
                    if (!Books::whereBook_id($book_id)->count() && Bookshelf::where(["user_id" => $request->user, "book_id" => $book_id, "state" => "active"])) {
                        $control = 0;
                    }
                }
                if (!$control) {
                    return redirect()->back();
                }
            }

            while (true) { //generate unique trade number
                $tradeNumber = numHash(Auth::user()->user_id + $request->user + rand(0, 10000));
                if (Trades::whereTrade_number($tradeNumber)->count() == 0) {
                    break;
                }
            }
            Trades::create([
                "trade_number" => $tradeNumber,
            ]);
            UsersTrades::create([
                "trade_number" => $tradeNumber,
                "user_id" => Auth::user()->user_id,
                "state" => "accepted",
            ]);
            UsersTrades::create([
                "trade_number" => $tradeNumber,
                "user_id" => $request->user,
            ]);


            foreach ($request->give as $book_id) {
                BooksTrades::create([
                    "trade_number" => $tradeNumber,
                    "user_id" => Auth::user()->user_id,
                    "book_id" => $book_id,
                ]);
            }
            foreach ($request->take as $book_id) {
                BooksTrades::create([
                    "trade_number" => $tradeNumber,
                    "user_id" => $request->user,
                    "book_id" => $book_id,
                ]);
            }

            sendNotification($request->user, "size bir takas teklifi yaptı", $tradeNumber);

            $state = "trade offer successful";

            return redirect()->route("mytrades", compact('state'));
        } else {
            return redirect()->back()->with('state', 'select book');
        }
    }

    function tradeOfferRefuse(Request $request)
    {
        $state = "error";
        //Trade Number Control
        $tnumber = $request->trade_number;
        $url = url()->previous();
        $arr = explode("/", $url);
        $urlTnumber = $arr[count($arr) - 1];
        if ($urlTnumber == $tnumber) {
            if (Trades::whereTrade_number($tnumber)->whereState('active')->count() == 1) { //Trade Control
                $c = UsersTrades::where(['trade_number' => $tnumber, 'user_id' => Auth::user()->user_id])->where('state', '!=', 'cancelled')->count(); //user state control
                if ($c == 1) {
                    $p1 = UsersTrades::whereTrade_number($tnumber)->whereUser_id(Auth::user()->user_id)->update([
                        "state" => "cancelled",
                    ]);
                    $p2 = Trades::whereTrade_number($tnumber)->update([
                        "state" => "cancelled",
                    ]);

                    if ($p1 && $p2) {
                        $state = "calcelled";
                        $user = UsersTrades::whereTrade_number($tnumber)->where('user_id', '!=', Auth::user()->user_id)->first()->user_id;
                        sendNotification($user, "takas teklifini reddetti", $tnumber);
                    } else {
                        $state = "db error";
                    }
                }
            }
        }

        return redirect()->back()->with('state', $state);
    }

    function tradeOfferAccept(Request $request)
    {
        $state = "error";
        //Trade Number Control
        $tnumber = $request->trade_number;
        $url = url()->previous();
        $arr = explode("/", $url);
        $urlTnumber = $arr[count($arr) - 1];
        if ($urlTnumber == $tnumber) {

            if (Trades::whereTrade_number($tnumber)->whereState('active')->count() == 1) { //Trade Control

                //users state control
                $tq = UsersTrades::where(['trade_number' => $tnumber, 'user_id' => Auth::user()->user_id, 'state' => 'standby']);
                $tqu = UsersTrades::where(['trade_number' => $tnumber, 'state' => 'accepted'])->where('user_id', '!=', Auth::user()->user_id);

                if ($tq->count() == 1 && $tqu->count() == 1) {

                    $p1 = $tq->update([
                        "state" => "accepted",
                    ]);
                    $p2 = Trades::whereTrade_number($tnumber)->update([
                        "state" => "accepted",
                    ]);

                    $booksAuth = BooksTrades::where(['trade_number' => $tnumber, 'user_id' => Auth::user()->user_id])->select('book_id')->get()->toarray();
                    $booksUser = BooksTrades::where('trade_number', '=', $tnumber)->where('user_id', '!=', Auth::user()->user_id)->select('book_id')->get()->toarray();

                    Bookshelf::whereUser_id(Auth::user()->user_id)->whereBook_id($booksAuth)->update(['state' => 'drop']);

                    Bookshelf::whereUser_id($tqu->first()->user_id)->whereBook_id($booksUser)->update(['state' => 'drop']);

                    //Cancel other active trades including this books
                    DB::table('trades')->join('users_trades', function ($join) {
                        $join->on('users_trades.trade_number', '=', 'trades.trade_number');
                        $join->where('users_trades.user_id', '=', Auth::user()->user_id);
                    })->join('books_trades', function ($join) use ($booksAuth) {
                        $join->on('books_trades.trade_number', '=', 'trades.trade_number');
                        $join->where('trades.state', '=', 'active');
                        $join->where('books_trades.book_id', '=', $booksAuth);
                    })->update([
                        'trades.state' => 'cancelled',
                    ]);
                    DB::table('trades')->join('users_trades', function ($join) use ($tqu) {
                        $join->on('users_trades.trade_number', '=', 'trades.trade_number');
                        $join->where('users_trades.user_id', '=', $tqu->first()->user_id);
                    })->join('books_trades', function ($join) use ($booksUser) {
                        $join->on('books_trades.trade_number', '=', 'trades.trade_number');
                        $join->where('trades.state', '=', 'active');
                        $join->where('books_trades.book_id', '=', $booksUser);
                    })->update([
                        'trades.state' => 'cancelled',
                    ]);



                    if ($p1 && $p2) {
                        $state = "accepted";
                        sendNotification($tqu->first()->user_id, "takas teklifini kabul etti", $tnumber);
                    } else {
                        $state = "db error";
                    }
                }
            }
        }

        return redirect()->back()->with('state', $state);
    }
    function tradeOfferDone(Request $request)
    {
        $state = "error";
        //Trade Number Control
        $tnumber = $request->trade_number;
        $url = url()->previous();
        $arr = explode("/", $url);
        $urlTnumber = $arr[count($arr) - 1];
        if ($urlTnumber == $tnumber) {

            if (Trades::whereTrade_number($tnumber)->whereState('accepted')->count() == 1) { //Trade Control

                //users state control
                $tq = UsersTrades::where(['trade_number' => $tnumber, 'user_id' => Auth::user()->user_id, 'state' => 'accepted']);
                $tqu = UsersTrades::where(['trade_number' => $tnumber])->where('user_id', '!=', Auth::user()->user_id)->where('state', '!=', 'cancelled');
                if ($tq->count() == 1 && $tqu->count() == 1) {

                    $p1 = $tq->update([
                        "state" => "done",
                    ]);
                    /* $p2 = Trades::whereTrade_number($tnumber)->update([
                        "state" => "accepted",
                    ]); */


                    if ($p1) {
                        $state = "done";
                        sendNotification($tqu->first()->user_id, "takası tamamladı", $tnumber);
                    } else {
                        $state = "db error";
                        return redirect()->back()->with('state', $state);
                    }
                    if ($tqu->first()->state == 'done') {
                        $p2 = Trades::whereTrade_number($tnumber)->update([
                            "state" => "done",
                        ]);
                        $booksAuth = BooksTrades::where(['trade_number' => $tnumber, 'user_id' => Auth::user()->user_id])->select('book_id')->get()->toarray();
                        $booksUser = BooksTrades::where('trade_number', '=', $tnumber)->where('user_id', '!=', Auth::user()->user_id)->select('book_id')->get()->toarray();

                        Bookshelf::whereUser_id(Auth::user()->user_id)->whereBook_id($booksAuth)->delete();

                        Bookshelf::whereUser_id($tqu->first()->user_id)->whereBook_id($booksUser)->delete();
                    }
                }
            }
        }

        return redirect()->back()->with('state', $state);
    }
    function tradeOfferGiveup(Request $request)
    {
        $state = "error";
        //Trade Number Control
        $tnumber = $request->trade_number;
        $url = url()->previous();
        $arr = explode("/", $url);
        $urlTnumber = $arr[count($arr) - 1];
        if ($urlTnumber == $tnumber) {
            if (Trades::whereTrade_number($tnumber)->whereState('accepted')->count() == 1) { //Trade Control
                $c = UsersTrades::where(['trade_number' => $tnumber, 'user_id' => Auth::user()->user_id])->where('state', '!=', 'cancelled')->count(); //user state control
                if ($c == 1) {
                    $p1 = UsersTrades::whereTrade_number($tnumber)->whereUser_id(Auth::user()->user_id)->update([
                        "state" => "cancelled",
                    ]);
                    $p2 = Trades::whereTrade_number($tnumber)->update([
                        "state" => "cancelled",
                    ]);

                    $tqu = UsersTrades::where(['trade_number' => $tnumber])->where('user_id', '!=', Auth::user()->user_id)->where('state', '!=', 'cancelled');
                    $booksAuth = BooksTrades::where(['trade_number' => $tnumber, 'user_id' => Auth::user()->user_id])->select('book_id')->get()->toarray();
                    $booksUser = BooksTrades::where('trade_number', '=', $tnumber)->where('user_id', '!=', Auth::user()->user_id)->select('book_id')->get()->toarray();

                    Bookshelf::whereUser_id(Auth::user()->user_id)->whereBook_id($booksAuth)->update(['state' => 'passive']);

                    Bookshelf::whereUser_id($tqu->first()->user_id)->whereBook_id($booksUser)->update(['state' => 'passive']);

                    if ($p1 && $p2) {
                        $state = "calcelled";
                        sendNotification($tqu->first()->user_id, "takastan vazgeçti", $tnumber);
                    } else {
                        $state = "db error";
                    }
                }
            }
        }

        return redirect()->back()->with('state', $state);
    }

    function tradeOfferUpdate(Request $request)
    {
        $request->validate([
            "user" => "required|numeric|exists:users,user_id",
            "trade_number" => "required|numeric|exists:trades,trade_number",
        ]);

        //Trade Number Control
        $tradeNumber = $request->trade_number;
        $url = url()->previous();
        $arr = explode("/", $url);
        $urlTnumber = $arr[count($arr) - 1];
        if ($urlTnumber != $request->trade_number) {
            return redirect()->back();
        }

        //If Trade is not Active or Trade Number and Users Is Not Matching
        if (!Trades::where(['trade_number' => $tradeNumber, 'state' => 'active'])->count() || !UsersTrades::where(['trade_number' => $tradeNumber, 'user_id' => $request->user])->count() || !UsersTrades::where(['trade_number' => $tradeNumber, 'user_id' => Auth::user()->user_id])->count()) {
            return redirect()->back();
        }

        //At least one book selected
        if ($request->give && $request->take) {
            //Given Books Control
            if ($request->give) {
                $control = 1;
                foreach ($request->give as $book_id) {
                    if (!Books::whereBook_id($book_id)->count() && Bookshelf::where(["user_id" => Auth::user()->user_id, "book_id" => $book_id])->count()) {
                        $control = 0;
                    }
                    //if book is passive but current in trade
                    if ($control == 0 && BooksTrades::where(["trade_number" => $tradeNumber, "user_id" => Auth::user()->user_id, "book_id" => $book_id])->count()) {
                        $control = 1;
                    }

                    if (!$control) {
                        return redirect()->back();
                    }
                }
            }

            //Taken Books Control
            if ($request->take) {
                $control = 1;
                foreach ($request->take as $book_id) {
                    if (!Books::whereBook_id($book_id)->count() || Bookshelf::where(["user_id" => $request->user, "book_id" => $book_id, 'state' => 'active'])->count() == 0) {
                        $control = 0;
                    }
                    //if book is passive but current in trade
                    if ($control == 0 && BooksTrades::where(["trade_number" => $tradeNumber, "user_id" => $request->user, "book_id" => $book_id])->count()) {
                        $control = 1;
                    }

                    if (!$control) {
                        return redirect()->back();
                    }
                }
            }

            //Users Trade States Update
            UsersTrades::where([
                "trade_number" => $tradeNumber,
                "user_id" => Auth::user()->user_id,

            ])->update([
                "state" => "accepted",
            ]);
            UsersTrades::where([
                "trade_number" => $tradeNumber,
                "user_id" => $request->user,
            ])->update([
                "state" => "standby",
            ]);;

            Trades::whereTrade_number($tradeNumber)->update([
                'state' => 'active',
            ]);


            //Delete Books
            BooksTrades::where([
                "trade_number" => $tradeNumber,
                "user_id" => Auth::user()->user_id
            ])->delete();
            BooksTrades::where([
                "trade_number" => $tradeNumber,
                "user_id" => $request->user
            ])->delete();


            //Add Books
            foreach ($request->give as $book_id) {
                BooksTrades::create([
                    "trade_number" => $tradeNumber,
                    "user_id" => Auth::user()->user_id,
                    "book_id" => $book_id,
                ]);
            }
            foreach ($request->take as $book_id) {
                BooksTrades::create([
                    "trade_number" => $tradeNumber,
                    "user_id" => $request->user,
                    "book_id" => $book_id,
                ]);
            }

            $state = "trade offer updated";
            sendNotification($request->user, "takas teklifini güncelledi", $tradeNumber);

            return redirect()->route("mytrades", compact('state'));
        } else {
            return redirect()->back()->with('state', 'select book');
        }
    }
}

function numHash($str, $len = 12)
{
    $binhash = md5($str, true);
    $numhash = unpack('N2', $binhash);
    $hash = $numhash[1] . $numhash[2];
    if ($len && is_int($len)) {
        $hash = substr($hash, 0, $len);
    }
    return $hash;
}

function sendNotification($user, $message, $trade_number)
{
    $created = NotificationModel::create([
        "user_id" => $user,
        "sender" => Auth::user()->user_id,
        "message" => $message,
        "link" => route('openTrade', $trade_number),
        "type" => "trade",
    ]);
    $notification_channel = NotificationChannels::whereUser($user)->first()->channel;
    $sender = Auth::user()->first_name . " " . substr(Auth::user()->last_name, 0, 1) . ".";
    $sender_photo = Auth::user()->user_photo;
    $link = route('openTrade', $trade_number);
    broadcast(new SendNotification($message, $sender, $sender_photo, $link, $notification_channel, $created->id))->toOthers();
}
