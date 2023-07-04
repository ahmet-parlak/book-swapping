<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Books;
use App\Models\Bookshelf;
use App\Models\Favorites;
use Intervention\Image\Facades\Image;
use Illuminate\support\Facades\DB;

class Admin extends Controller
{
    function panel()
    {
        return view('admin/panel');
    }

    function books(Request $request)
    {
        if ($request->get("search")) {
            $books = Books::where("book_name", "like", "%" . $request->get("search") . "%")
                ->orWhere("author", "like", "%" . $request->get("search") . "%")
                ->orWhere("publisher", "like", "%" . $request->get("search") . "%")->orderBy("state", "desc")
                ->orWhere("isbn", "like", "%" . $request->get("search") . "%")->orderBy("state", "desc")
                ->orWhere("state", "like", "%" . $request->get("search") . "%")
                ->orderBy("demand", "desc")->simplePaginate(10);
            $book_state = Books::groupBy('state')->select('state', DB::raw('count(*) as total'))->get();
        } else {
            $books = Books::orderBy("state", "desc")
                ->orderBy("demand", "desc")->simplePaginate(10);
            $book_state = Books::groupBy('state')->select('state', DB::raw('count(*) as total'))->get();
        }

        return view('admin/books', compact('books'), compact('book_state'));
    }


    /*########## BOOK INSERT ##########*/
    function addBookView()
    {
        return view('admin/addbook');
    }

    function addBook(Request $request)
    {
        $request->validate([
            "isbn" => "required|digits:13",
            "bookName" => "required",
            "author" => "required",
            "publisher" => "required",
            "publishYear" => "digits:4",
            "state" => "required",
            "image" => "image|max: 1024",
        ], [
            'image.image' => 'Lütfen bir resim dosyası yükleyin!',
            'image.max' => 'Dosya boyutu en fazla 1 MB (1024 KB) olabilir!',
            'state.required' => 'Lütfen kitap durumunu belirleyin',
            'publishYear.digits' => 'Yıl bilgisi 4 haneden oluşmalı!',
            'publisher.required' => 'Kitabın yayınevi bilgisi boş olamaz!',
            'author.required' => 'Kitabın yazar bilgisi boş olamaz!',
            'bookName.required' => 'Kitabın isim bilgisi boş olamaz!',
            'isbn.digits' => 'ISBN bilgisi 13 haneden ve sadece sayılardan oluşmalı!',
            'book_id.required' => 'Hata, sayfayı yeniden yükleyin!',
        ]);

        if (Books::whereIsbn($request->isbn)->first()) {
            return redirect()->route("panelUpdateBook", ['book' => Books::whereIsbn($request->isbn)->first()->book_id, 'pageState' => 'Book Exist']);
        } else {

            $photoName = "default.jpg"; //default book image

            /* Book Image */
            if ($request->image) {
                $photoName = md5($request->isbn) . "." . $request->image->getClientOriginalExtension(); //name+ext.
                $upload = Image::make($request->image)->resize(280, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save('media/books/' . $photoName);
            }

            $state = Books::create([
                "isbn" => $request->isbn,
                "book_name" => $request->bookName,
                "author" => $request->author,
                "publisher" => $request->publisher,
                "publication_year" => $request->publishYear,
                "state" => $request->state,
                "image" => "media/books/" . $photoName,
            ]);
            if ($state) {
                return redirect()->route("panelAddBook", ['pageState' => 'Book Added']);
            } else {
                return redirect()->route("panelAddBook", ['pageState' => 'Insert Error']);
            }
        }
    }


    /*########## BOOK UPDATE ##########*/

    function updateBookView(Request $request)
    {
        if (request()->get('book') && Books::find(request()->get('book'))) {
            $book = Books::find(request()->get('book'));
            return view('admin/updatebook', compact('book'));
        }
        return redirect()->route("panelBooks");
    }

    function updateBook(Request $request)
    {

        $request->validate([
            "book_id" => "required",
            "isbn" => "digits:13",
            "bookName" => "required",
            "author" => "required",
            "publisher" => "required",
            "publishYear" => "digits:4",
            "state" => "required",
            "image" => "image|max: 1024",
        ], [
            'image.image' => 'Lütfen bir resim dosyası yükleyin!',
            'image.max' => 'Dosya boyutu en fazla 1 MB (1024 KB) olabilir!',
            'state.required' => 'Lütfen kitap durumunu belirleyin',
            'publishYear.digits' => 'Yıl bilgisi 4 haneden oluşmalı!',
            'publisher.required' => 'Kitabın yayınevi bilgisi boş olamaz!',
            'author.required' => 'Kitabın yazar bilgisi boş olamaz!',
            'bookName.required' => 'Kitabın isim bilgisi boş olamaz!',
            'isbn.digits' => 'ISBN bilgisi 13 haneden ve sadece sayılardan oluşmalı!',
            'book_id.required' => 'Hata, sayfayı yeniden yükleyin!',

        ]);


        /* Book Image */
        $book = Books::whereBook_id($request->book_id)->first();
        $photoName = $book->image;


        if ($request->image) {
            if ($request->isbn) { //if isbn is to be updated we need new isbn
                $photoName = md5($request->isbn) . "." . $request->image->getClientOriginalExtension(); //name+ext.
            } else { //else we use current isbn
                $photoName = md5($book->isbn) . "." . $request->image->getClientOriginalExtension(); //name+ext.
            }
            $upload = Image::make($request->image)->resize(280, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save('media/books/' . $photoName);
        }


        if ($book) {
            $state = Books::whereBook_id($request->book_id)->update([
                "isbn" => $request->isbn ? $request->isbn : $book->isbn,
                "book_name" => ucfirst($request->bookName),
                "author" => ucfirst($request->author),
                "publisher" => ucfirst($request->publisher),
                "publication_year" =>  $request->publishYear,
                "state" => $request->state,
                "image" => $request->image ? 'media/books/' . $photoName : $photoName,
            ]);
            if ($state) {
                return redirect()->route("panelUpdateBook", ['book' => $request->book_id, 'pageState' => 'Book Updated']);
            } else {
                return redirect()->route("panelUpdateBook", ['pageState' => 'Update Error']);
            }
        } else {
            return redirect()->back()->with(['pageState' => 'Book Not Exist']);
        }
    }


    /*########## BOOK DELETE ##########*/

    function deleteBookView(Request $request)
    {
        $book = Books::whereBook_id($request->get('book'))->first();
        if ($book) {
            return view("admin/deletebook", compact('book'));
        } else {
            return redirect()->route('panelBooks');
        }
    }

    function deleteBook(Request $request)
    {
        if (Books::whereBook_id($request->book_id)->count()) {
            $bookInTrade = DB::table('trades')->join('books_trades', 'trades.trade_number', '=', 'books_trades.trade_number')->where('book_id', '=', $request->book_id)->count();
            if ($bookInTrade) {
                return redirect()->route('panelBooks', ['pageState' => 'Delete Error ','error'=>'book in trade']);
            }
            Books::whereBook_id($request->book_id)->delete();
            Bookshelf::whereBook_id($request->book_id)->delete();
            Favorites::whereBook_id($request->book_id)->delete();
            return redirect()->route('panelBooks', ['pageState' => 'Delete Success']);
        } else {
            return redirect()->route('panelBooks');
        }
    }
}
