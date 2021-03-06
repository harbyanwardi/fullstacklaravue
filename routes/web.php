<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function(){
 return view('auth.login');
}); //halaman pertama

Auth::routes();
Route::match(["GET", "POST"], "/register", function(){
 return redirect("/login");
})->name("register"); //route jika link register diakses maka akan dilempar ke login

Route::get('/home', 'HomeController@index')->name('home');

Route::resource("users", "UserController");
Route::get('/categories/{id}/restore', 'CategoryController@restore')->name('categories.restore'); //route manual harus diatas route resource
Route::get('/categories/trash', 'CategoryController@trash')->name('categories.trash');
Route::resource('categories', 'CategoryController');

Route::get('books/trash', 'BookController@trash')->name('books.trash');
Route::get('/books/{id}/restore', 'BookController@restore')->name('books.restore');
Route::resource('books', 'BookController');
Route::get('/ajax/categories/search', 'CategoryController@ajaxSearch');

Route::resource('order', 'OrderController');


