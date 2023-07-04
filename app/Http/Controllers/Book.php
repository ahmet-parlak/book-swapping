<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Books;
use App\Models\Favorites;
use App\Models\Bookshelf;

use function PHPUnit\Framework\isNull;

class Book extends Controller
{
    public function book(Request $request)
    {

        if ($request->get("book")) {
            $favorite = 0;
            $bookshelf = 0;
            $users = 0;
            $book = DB::table("books")
                ->leftJoin("bookshelf", function ($join) {
                    $join->on("books.book_id", "=", "bookshelf.book_id");
                    $join->where("bookshelf.state", "=", "active");
                })
                ->select("books.*", DB::raw('count(bookshelf.user_id) as intrade'))
                ->where(["books.book_id" => $request->get('book'), "books.state" => "active"])->groupBy("book_id")->first();

            if (Favorites::where(['book_id' => $request->get("book"), 'user_id' => Auth::user()->user_id])->first()) {
                $favorite = 1;
            }
            if (Bookshelf::where(['book_id' => $request->get("book"), 'user_id' => Auth::user()->user_id])->first()) {
                $bookshelf = 1;
            }

            if ($book) { //Kitap mevcutsa
                if ($book->intrade) { //Takasta kullanıcı varsa
                    $users = DB::table("bookshelf")
                        ->join("users", "bookshelf.user_id", "=", "users.user_id")
                        ->select("users.*")
                        ->where(["book_id" => $request->get('book'), "bookshelf.state" => "active"])->orderByRaw(
                            "CASE WHEN city = '" . Auth::user()->city ."' THEN 1 ELSE 2  END ASC"
                        )->get();
                }
                return view("pages/book", compact(['book', 'favorite', 'bookshelf', 'users']));
            }
            return redirect()->route("store");
        } else {
            return redirect()->route("store");
        }
    }
}
