<?php


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route; 

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

Route::get('/', function () {
    return view('/welcome');
});

Route::get('/test', function () {
    return view('test');

});

Route::get('/pt', function () {
    return view('pt'); 
});

Route::get('/myDates', function () {
    return view('myDates'); 
});

// # CRUD DATOS FISCALES
Route::get('/mail_token', [App\Http\Controllers\AuthController::class, 'mail_token'])->name('mail_token');

// # CRUD DATOS FISCALES - Datos del
Route::resource('/create_dates', App\Http\Controllers\DateFiscoController::class); 


Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

