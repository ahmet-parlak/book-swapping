<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Books;
use  App\Models\Favorites;
use  App\Models\Bookshelf;
use Illuminate\Support\Facades\Auth;
use Illuminate\support\Facades\DB;

class Store extends Controller
{
    public function store(Request $request)
    {
        $books = 0;
        $topFavorites = [];
        if (Books::whereState('active')->first()) { //If Active book exists (at least one)

            if ($request->get("search")) { //If book searching
                $books = DB::table("books")
                    ->leftJoin("bookshelf", function ($join) {
                        $join->on("books.book_id", "=", "bookshelf.book_id");
                        $join->where("bookshelf.state", "=", "active");
                    })
                    ->select("books.*", DB::raw('count(bookshelf.user_id) as intrade'))
                    ->where("books.state", "active")
                    ->where("book_name", "like", "%" . $request->get("search") . "%")
                    ->orWhere("author", "like", "%" . $request->get("search") . "%")
                    ->orWhere("publisher", "like", "%" . $request->get("search") . "%")
                    ->orWhere("isbn", "like", "%" . $request->get("search") . "%")->orderBy("state", "desc")
                    ->groupBy("books.book_id")->simplePaginate(10)->withPath('?search=' . $request->get("search"));;

                /* URL Page Param. Manipulate Control */
                if ($books->currentPage() > 1 && $books->count() == 0) {
                    return redirect()->route('store');
                }
            } else {
                $books = DB::table("books")
                    ->leftJoin("bookshelf", function ($join) {
                        $join->on("books.book_id", "=", "bookshelf.book_id");
                        $join->where("bookshelf.state", "=", "active");
                    })
                    ->select("books.*", DB::raw('count(bookshelf.user_id) as intrade'))
                    ->where("books.state", "active")->groupBy("books.book_id")->orderByDesc('intrade')->limit(8)->get();

                $topFavorites = DB::table('books')->join('favorites', 'favorites.book_id', '=', 'books.book_id')->groupBy('favorites.book_id')->select('books.*', DB::raw('count(favorites.book_id) as total'))->orderBy('total', 'desc')->limit(6)->get();
            }
        }
        $favorites = [];
        $bookshelf = [];
        foreach (Favorites::whereUser_id(Auth::user()->user_id)->select('book_id')->get() as $key) {
            array_push($favorites, $key->book_id);
        }
        foreach (Bookshelf::where(['user_id' => Auth::user()->user_id])->select('book_id')->get() as $key) {
            array_push($bookshelf, $key->book_id);
        }



        return view("pages/store", compact(['books', 'favorites', 'bookshelf', 'topFavorites']));
    }



    public function addBookForm()
    {
        return view("pages/addbook");
    }

    public function addBook(Request $request)
    {
        $request->validate([
            "isbn" => "digits:13|required",
            "bookName" => "required",
        ], [
            'bookName.required' => 'Kitap bilgisi boş bırakılamaz!',
            'isbn.required' => 'ISBN bilgisi boş bırakılamaz!',
            'isbn.digits' => 'ISBN bilgisi 13 haneden ve sadece sayılardan oluşmalı!',
        ]);

        $book = Books::whereIsbn($request->isbn)->whereState("waiting");

        if ($book->first()) { //If book already exist and waiting
            $state = $book->update(["demand" => $book->first()->demand + 1,]);
        } elseif (Books::whereIsbn($request->isbn)->first()) { //Else if book already exist and in store or passive
            $state = true;
        } else {
            $author = $request->author;

            if (!$request->author) {
                $author = "";
            }

            $state = Books::create([
                "isbn" => $request->isbn,
                "book_name" => $request->bookName,
                "author" => $author,
                "state" => "waiting",
                "demand" => 1,
            ]);
        }

        if ($state) {
            $state = "addBooksuccess";
        } else {
            $state = "addBookerror";
        }

        return redirect()->route("addbook", compact("state"));
    }
}
