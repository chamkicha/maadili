<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Sectiontaarafa478;


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


Route::get('/login', function () {
    $response = ['message' => 'Unauthorized please login again'];
    return response()->json($response,401);
})->name('login');
