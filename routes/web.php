<?php

use Illuminate\Support\Facades\Route;
use App\Models\Sectiontaarafa478;
use App\Models\User;
use App\Http\Controllers\Kiongozi\KiongoziController;

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

// Route::get('/test', function () {
//     // $viongozi = User::where('phone_number', '255753158796')->get();
//     // $taarifaAjiras = Sectiontaarafa478::where('user_id',$viongozi->id)->get();
//     $viongozi = User::where('file_number', null)->get();
//     $Ajira = [];
//     foreach($viongozi as $kiongozi){
//         $taarifaAjiras = Sectiontaarafa478::where('user_id',$kiongozi->id)->latest()->first();
//         if($taarifaAjiras !== null){

//             $fileNumber = file_number($taarifaAjiras);
//             $user = User::where('id','=',$kiongozi->id)->first();
//             $user->file_number= $fileNumber;
//             $user->save();
//             $Ajira[] = $kiongozi->id.' - '.$fileNumber;

//         }
//     }

//     dd($Ajira);

//     return view('welcome');
// });

Route::get('/login', function () {
    $response = ['message' => 'Unauthorized please login again'];
    return response()->json($response,401);
})->name('login');
