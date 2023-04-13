<?php

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Declaration\userDeclarationController;
use App\Http\Controllers\Family\familyMemberController;
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

Route::middleware('auth:sanctum')->group( function () {
    Route::get('user',function (Request $request){
        return $request->user();
    });
    Route::post('logout',[AuthenticationController::class,'logout']);

    Route::controller(userDeclarationController::class)->group(function (){
        Route::get('declarations', 'declarations');
        Route::get('declaration/form/{secure_token?}', 'declarationForm');
        Route::post('submit/declaration', 'declarationSubmission');
        Route::get('declaration/preview', 'previewAdf');
        Route::get('declaration/download', 'downloadAdf');
    });

    Route::controller(familyMemberController::class)->group(function (){
        Route::post('register/family-member', 'addFamilyMember');
        Route::get('family-members', 'getFamilyMembers');
        Route::get('edit/family-member', 'editFamilyMember');
    });
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
    Route::get('uuid', 'uuid');
});
