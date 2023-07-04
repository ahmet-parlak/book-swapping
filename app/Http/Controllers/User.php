<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\support\Facades\DB;
use App\Models\Users;
use App\Models\Bookshelf;
use App\Models\Books;

class User extends Controller
{
    function userView(Request $request)
    {
        $user = $request->get("user");
        //if user parameter exists and user exist and user not current auth user
        if ($user && Users::find($user) && Auth::user()->user_id != $user) {
            $user = Users::find($user);
            $books = DB::table("bookshelf")
                ->join("books", function ($join) {
                    $join->on("books.book_id", "=", "bookshelf.book_id");
                    $join->where("books.state", "=", "active");
                })
                ->where("bookshelf.user_id", "=", $user->user_id)->where("bookshelf.state", "=", "active")
                ->select("books.*")->paginate(5)->withPath('/user?user=' . $user->user_id);

            if ($books->total() == 0) {
                return redirect()->route("store", ['state' => 'userBookNotFound']);
            }

            $successTrade = DB::table('users_trades')->join('trades', function ($join) {
                $join->on('users_trades.trade_number', '=', 'trades.trade_number');
                $join->where('trades.state', '=', 'done');
            })->where('user_id', '=', $user->user_id)->count();

            /* URL Page Param. Manipulate Control */
            if ($books->currentPage() > 1 && $books->count() == 0) {
                return redirect()->route('store');
            }

            return view('pages/user', compact(['user', 'books', 'successTrade']));
        }
        return redirect()->route("store");
    }
}
