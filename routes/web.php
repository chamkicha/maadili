<?php

use App\Models\User;
use App\Models\Region;
use App\Models\Sectiontaarafa478;
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
    return view('welcome');
});



Route::get('/maadili', function () {
    $users  = Sectiontaarafa478::with('userDetails')->get();
    foreach($users as $value){

        $kanda = Region::where('id',$value->mkoa_sasa)->first();
        if($kanda){

        $zone_id = $kanda->zone_id;

        if($value->userDetails){
            $user = User::where('id','=',$value->userDetails->id)->first();
            $user->zone_id = $zone_id;
            $user->save();
        }
        }

    }

    return '$user';
});




Route::get('/login', function () {
    $response = ['message' => 'Unauthorized please login again'];
    return response()->json($response,401);
})->name('login');
