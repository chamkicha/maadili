<?php

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\MetaData\lookUpDataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('login', [AuthenticationController::class,'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(lookUpDataController::class)->group(function () {
    Route::get('countries', 'country');
    Route::get('regions', 'regions');
    Route::get('districts/{RegionCode?}', 'districts');
    Route::get('wards/{LgaCode?}', 'wards');
    Route::get('sex', 'sex');
    Route::get('marital-statuses', 'maritalStatus');
    Route::get('building-type', 'buildingType');
    Route::get('titles', 'titles');
    Route::get('offices', 'offices');
    Route::get('employment-type', 'employmentType');
    Route::get('declaration-type', 'declarationType');
    Route::get('family-member-type', 'familyMemberType');
    Route::get('type-of-use', 'typeOfUse');
    Route::get('source-of-income', 'sourceOfIncome');
    Route::get('property-type', 'propertyType');
    Route::get('transport-types', 'transportTypes');
    Route::get('debt-types', 'debtTypes');
});
