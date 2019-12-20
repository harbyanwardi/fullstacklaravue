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
Route::get('/categories/{id}/restore', 'CategoryController@restore')->name('categories.restore');
Route::resource('categories', 'CategoryController');
Route::get('/cat/trash', 'CategoryController@trash')->name('categories.trash'); //route resource dengan manual harus dibedakan namanya

