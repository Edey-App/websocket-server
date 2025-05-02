<?php

use App\Events\TestMessage;
use App\Events\testWebsocket;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// Route::get('test', [TestController::class, 'test']);
Route::view('uche', 'checkingWebsocket');


Route::get('/test-websocket', function () {
    return view('test-websocket');
});
Route::post('/send-websocket-message', function (\Illuminate\Http\Request $request) {
    event(new TestMessage($request->message));
    return ['success' => true];
});