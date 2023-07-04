<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\support\Facades\DB;
use App\Models\Favorites as FavoritesModel;
use App\Models\Books;

use Symfony\Component\Console\Input\Input;

class Favorites extends Controller
{
    function viewFavorites()
    {

        if (!FavoritesModel::whereUser_id(Auth::user()->user_id)->first()) {
            $books = [];
        } else {
            $books = DB::table("favorites")
                ->join("books", "favorites.book_id", "=", "books.book_id")
                ->where('books.state','=','active')
                ->whereUser_id(Auth::user()->user_id)->select("books.*")->paginate(5);
            /* URL Page Param. Manipulate Control */
        if ($books->currentPage() > 1 && $books->count() == 0) {
            return redirect()->route('favorites');
        }
        }

        

        return view('pages/favorites', compact(('books')));
    }

    function addFavorites(Request $request)
    {
        $input = json_decode(array_keys($request->input())[0]);

        if ($input->request == "addFavorites") {

            /* If book don't exist or book already be added*/
            if (!Books::find($input->book)) {
                return response()->json([
                    "state" => "error",
                    "error" => "not found"
                ], 200);
            }
            if (FavoritesModel::where(['user_id' => Auth::user()->user_id, 'book_id' => $input->book])->first()) {
                return response()->json([
                    "state" => "error",
                    "error" => "exist"
                ], 200);
            }

            FavoritesModel::create([
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

    function removeFavorites(Request $request)
    {
        $input = json_decode(array_keys($request->input())[0]);
        
        if ($input->request == "removeFavorites") {

            if(FavoritesModel::where(['user_id' => Auth::user()->user_id, 'book_id' => $input->book])->first()){
                FavoritesModel::where(['user_id' => Auth::user()->user_id, 'book_id' => $input->book])->delete();
            }
          
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
}
