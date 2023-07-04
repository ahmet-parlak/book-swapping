<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home;
use App\Http\Controllers\Signin;
use App\Http\Controllers\Login;
use App\Http\Controllers\Logout;
use App\Http\Controllers\Myprofile;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Store;
use App\Http\Controllers\Book;
use App\Http\Controllers\Favorites;
use App\Http\Controllers\Bookshelf;
use App\Http\Controllers\User;
use App\Http\Controllers\Trade;
use App\Http\Controllers\Messages;
use App\Http\Controllers\Notifications;


Route::get('/', [Home::class, 'viewHome'])->name("home");

Route::get('login', [Login::class, 'viewLogin'])->name("login");
Route::post('login', [Login::class, 'authenticate'])->name("login.action");


Route::get('logout', [Logout::class, 'logout'])->name("logout");


Route::get('signin', [Signin::class, 'viewSignin'])->name("signin");
Route::post('signin', [Signin::class, 'register'])->name("signin");


Route::get('store', [Store::class, 'store'])->middleware('auth')->name("store");

Route::get('myprofile', [Myprofile::class, 'viewMyProfile'])->middleware('auth')->name("myprofile");
Route::post('myprofile', [Myprofile::class, 'editProfile'])->middleware('auth')->name("myprofile.action");
Route::post('upload-pp', [Myprofile::class, 'uploadPP'])->middleware('auth')->name("upload-pp");


Route::get('book', [Book::class, 'book'])->middleware('auth')->name("book");

Route::get('user', [User::class, 'userView'])->middleware('auth')->name("user");

Route::get('bookshelf', [Bookshelf::class, 'viewBookshelf'])->middleware('auth')->name("bookshelf");
Route::post('addbookshelf', [Bookshelf::class, 'addBookshelf'])->middleware('auth')->name("addBookshelf");
Route::post('removebookshelf', [Bookshelf::class, 'removeBookshelf'])->middleware('auth')->name("removeBookshelf");
Route::post('activateBookshelf', [Bookshelf::class, 'activate'])->middleware('auth')->name("activateBookshelf");
Route::post('disableBookshelf', [Bookshelf::class, 'disable'])->middleware('auth')->name("disableBookshelf");



Route::get('favorites', [Favorites::class, 'viewFavorites'])->middleware('auth')->name("favorites");
Route::post('addFavorites', [Favorites::class, 'addFavorites'])->middleware('auth')->name("addFavorites");
Route::post('removeFavorites', [Favorites::class, 'removeFavorites'])->middleware('auth')->name("removeFavorites");

/* Trades */
Route::get('mytrades', [Trade::class, 'mytrades'])->middleware('auth')->name("mytrades");
Route::get('mytrades/trade/{trade_number}', [Trade::class, 'openTrade'])->middleware('auth')->name("openTrade");
Route::get('trade', [Trade::class, 'tradeView'])->middleware('auth')->name("trade");
Route::post('tradeoffer', [Trade::class, 'tradeOffer'])->middleware('auth')->name("tradeoffer");
Route::post('tradeofferupdate', [Trade::class, 'tradeOfferUpdate'])->middleware('auth')->name("tradeofferupdate");
Route::post('tradeofferaccept', [Trade::class, 'tradeOfferAccept'])->middleware('auth')->name("tradeofferaccept");
Route::post('tradeofferrefuse', [Trade::class, 'tradeOfferRefuse'])->middleware('auth')->name("tradeofferrefuse");
Route::post('tradeofferdone', [Trade::class, 'tradeOfferDone'])->middleware('auth')->name("tradeofferdone");
Route::post('tradeoffergiveup', [Trade::class, 'tradeOfferGiveup'])->middleware('auth')->name("tradeoffergiveup");

/* Messages */
Route::get('messages', [Messages::class, 'myMessages'])->middleware('auth')->name("messages");
Route::get('message/{user}',[Messages::class, 'toUser'])->middleware('auth')->name("toUser");
Route::post('sendmessage',[Messages::class, 'sendMessage'])->middleware('auth')->name("sendMessage");


Route::get('info', function () {
    return view('pages/info');
})->middleware('auth')->name("info");

Route::get('addbook', [Store::class, 'addBookForm'])->middleware('auth')->name("addbook");
Route::post('addbook', [Store::class, 'addBook'])->middleware('auth')->name("addbook");

/* Notification */
Route::post('markasread',[Notifications::class,'markAsRead'])->middleware('auth')->name("markAsRead");
Route::post('markasreadall',[Notifications::class,'markAsReadAll'])->middleware('auth')->name("markAsReadAll");


//Administration 
Route::group(['middleware' => ['auth', 'isAdmin'], 'prefix' => 'admin'], function () {
    Route::get("panel", [Admin::class, 'panel'])->name("panel");
    Route::get("users", [Admin::class, 'panel'])->name("panelUsers");
    Route::get("books", [Admin::class, 'books'])->name("panelBooks");
    Route::get("abook", [Admin::class, 'addbookView'])->name("panelAddBook");
    Route::post("addnewbook", [Admin::class, 'addbook'])->name("panelAddBook.action");
    Route::get("updatebook", [Admin::class, 'updatebookView'])->name("panelUpdateBook");
    Route::post("updatebook", [Admin::class, 'updateBook'])->name("panelUpdateBook.action");
    Route::get("deletebook", [Admin::class, 'deleteBookView'])->name("panelDeleteBook");
    Route::post("deletebook", [Admin::class, 'deleteBook'])->name("panelDeleteBook.action");
});
