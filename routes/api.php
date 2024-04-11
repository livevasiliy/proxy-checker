<?php declare(strict_types=1);

use App\Events\MyEvent;
use App\Http\Controllers\ProxyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::prefix('v1')->group(function () {
   Route::post('proxy/check', [ProxyController::class, 'check']);
});

Route::get('test', function () {
    event(new MyEvent('hello world'));
});
