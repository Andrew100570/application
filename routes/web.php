<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ApiController;
use Elastic\Elasticsearch\ClientBuilder;
use App\Models\Ticket;
use App\Models\Message;
use App\Http\Controllers\SendController;
use App\Http\Controllers\RabbitController;
use App\Http\Controllers\FastDeliveryController;
use App\Http\Controllers\SlowDeliveryController;

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
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::prefix('admin')->middleware('Admin')->group(function () {
    Route::get('/' , [AdminController::class,'index'])->name('admin');
    Route::post('/' , [AdminController::class,'addTicket']);
    Route::get('/ticket' , [AdminController::class,'ticket'])->name('ticket');
});

Route::get('/send-email', [FeedbackController::class,'send'])->middleware('Admin');

Route::post('price', [FastDeliveryController::class, 'calculation']);
Route::post('slowPrice', [SlowDeliveryController::class, 'calculation']);
