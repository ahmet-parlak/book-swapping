<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\support\Facades\DB;
use App\Models\Books;
use App\Models\Trades;
use App\Models\BooksTrades;

use App\Models\Bookshelf as BookshelfModel;

class Bookshelf extends Controller
{
    function viewBookshelf()
    {

        $books = DB::table("bookshelf")->join("books", "bookshelf.book_id", "=", "books.book_id")
            ->whereUser_id(Auth::user()->user_id)->where('bookshelf.state','!=','drop')->where('books.state','=', 'active')->select("books.*", "bookshelf.state as bookshelf_state")->paginate(10);


        /* URL Page Param. Manipulate Control */
        if ($books->count() == 0 && $books->currentPage() > 1) {
            return redirect()->route('bookshelf');
        }

        return view("pages/bookshelf", compact('books'));
    }

    function addBookshelf(Request $request)
    {
        $input = json_decode(array_keys($request->input())[0]);
        if ($input->request == "addBookshelf") {

            /* If book don't exist or book already be added*/
            if (!Books::find($input->book)) {
                return response()->json([
                    "state" => "error",
                    "error" => "not found"
                ], 200);
            }
            if (BookshelfModel::where(['user_id' => Auth::user()->user_id, 'book_id' => $input->book])->first()) {
                return response()->json([
                    "state" => "error",
                    "error" => "exist"
                ], 200);
            }

            BookshelfModel::create([
                "book_id" => $input->book,
                "user_id" => Auth::user()->user_id,
            ]);

            return response()->json([
                "state" => "success",
                "error" => ""
            ], 200);
        } else {
            return response()->json([
                "state" => "error",
                "error" => "request error"
            ], 200);
        }
    }

    function removeBookshelf(Request $request)
    {
        $input = json_decode(array_keys($request->input())[0]);
        if ($input->request == "remove") {

            /* If book don't exist or book already be added*/
            if (!Books::whereBook_id($input->book)->count()) {
                return response()->json([
                    "state" => "error",
                    "error" => "not exist"
                ], 200);
            }

            $inTrade = DB::table('trades')->join('books_trades', function ($join) use ($input) {
                $join->on('trades.trade_number', '=', 'books_trades.trade_number');
                $join->where('books_trades.book_id', '=', $input->book);
                $join->where('trades.state', '=', 'active');
                $join->where('books_trades.user_id', '=', Auth::user()->user_id);
            })->count();

            if ($inTrade) {
                return response()->json([
                    "state" => "inTrade",
                    "error" => ""
                ], 200);
            }

            if (BookshelfModel::where(['user_id' => Auth::user()->user_id, 'book_id' => $input->book])->first()) {
                BookshelfModel::where(['user_id' => Auth::user()->user_id, 'book_id' => $input->book])->delete();
                return response()->json([
                    "state" => "success",
                    "error" => ""
                ], 200);
            }
            return response()->json([
                "state" => "error",
                "error" => "error"
            ], 200);
        } else {
            return response()->json([
                "state" => "error",
                "error" => "request error"
            ], 200);
        }
    }

    function activate(Request $request)
    {
        $input = json_decode(array_keys($request->input())[0]);
        if ($input->request == "activate") {

            /* If book don't exist or book already be added*/
            if (!Books::find($input->book)) {
                return response()->json([
                    "state" => "error",
                    "error" => "not found"
                ], 200);
            }
            if (BookshelfModel::where(['user_id' => Auth::user()->user_id, 'book_id' => $input->book])->where('state','=','passive')->first()) {
                BookshelfModel::where(['user_id' => Auth::user()->user_id, 'book_id' => $input->book])->update(["state" => "active"]);
                return response()->json([
                    "state" => "success",
                    "error" => ""
                ], 200);
            }
            return response()->json([
                "state" => "error",
                "error" => "error"
            ], 200);
        } else {
            return response()->json([
                "state" => "error",
                "error" => "request error"
            ], 200);
        }
    }

    function disable(Request $request)
    {
        $input = json_decode(array_keys($request->input())[0]);
        if ($input->request == "disable") {

            /* If book don't exist or book already be added*/
            if (!Books::find($input->book)) {
                return response()->json([
                    "state" => "error",
                    "error" => "not found"
                ], 200);
            }
            if (BookshelfModel::where(['user_id' => Auth::user()->user_id, 'book_id' => $input->book])->first()) {
                BookshelfModel::where(['user_id' => Auth::user()->user_id, 'book_id' => $input->book])->update(["state" => "passive",]);
                return response()->json([
                    "state" => "success",
                    "error" => ""
                ], 200);
            }
            return response()->json([
                "state" => "error",
                "error" => "error"
            ], 200);
        } else {
            return response()->json([
                "state" => "error",
                "error" => "request error"
            ], 200);
        }
    }
}
