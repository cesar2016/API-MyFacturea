<?php

use App\Http\Controllers\API\AFIPController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TraineeController;
use App\Http\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes Sanctum: Guia https://www.youtube.com/watch?v=n-J3zw4OWmI&t=982s
|--------------------------------------------------------------------------
| TOKEn-Tedsting: 2|BqRd0l6mle6IKCIyGOyexF8BNxV73zGahKk0FI0D
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// # Del Logeo de uausio
Route::post('/register',[ AuthController::class, 'register']);
Route::post('/login',[ AuthController::class, 'login']);


Route::group(['middleware' => ['auth:sanctum']], function() {

    Route::post('/info_user',[ AuthController::class, 'info_user']); // Obtener indo del User 
    // # Metodos del SDK AFIP
    Route::get('/invoice', [AFIPController::class, 'invoice'])->name('invoice');
    Route::post('/datesBussin', [AFIPController::class, 'datesBussin'])->name('datesBussin'); 
    Route::post('/datesPerson', [AFIPController::class, 'datesPerson'])->name('datesPerson'); 
    Route::get('/statusService', [AFIPController::class, 'statusService'])->name('statusService');
    /*Route::get('/pointSale', [AFIPController::class, 'pointSale'])->name('pointSale');
    Route::get('/typeVoucher', [AFIPController::class, 'typeVoucher'])->name('typeVoucher');
    Route::get('/typeConcepts', [AFIPController::class, 'typeConcepts'])->name('typeConcepts');
    Route::get('/typeDocuments', [AFIPController::class, 'typeDocuments'])->name('typeDocuments');    
    Route::get('/barCode', [AFIPController::class, 'barCode'])->name('barCode');
    Route::get('/qr', [AFIPController::class, 'qr'])->name('qr');
    Route::get('/idUser', [Controller::class, 'idUser'])->name('idUser');*/ 
    
    // # Invoices 
    Route::post('/create_invoice_C', [AFIPController::class, 'create_invoice_C'])->name('create_invoice_C'); 
    // # Just trainee
    Route::get('/operation', [TraineeController::class, 'operation']);

    Route::get('/prueba', [TraineeController::class, 'prueba']);

     

});

 








