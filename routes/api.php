<?php

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Kiongozi\BinafsiController;
use App\Http\Controllers\Kiongozi\KiongoziController;
use App\Http\Controllers\Auth\passwordUpdateController;
use App\Http\Controllers\Declaration\userDeclarationController;
use App\Http\Controllers\Family\familyMemberController;
use App\Http\Controllers\MetaData\lookUpDataController;
use App\Http\Controllers\Notification\notificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\forgotPasswordController;
use App\Http\Controllers\RejestaZawadi\rejestaZawadiController;

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
Route::post('reset-password', [forgotPasswordController::class,'sendResetPassword']);

Route::post('declaration/auth/download', [userDeclarationController::class,'downloadAdfAuth']);

Route::middleware('auth:sanctum')->group( function () {
    Route::get('user',function (Request $request){
        return $request->user();
    });
    Route::post('logout',[AuthenticationController::class,'logout']);

    Route::controller(passwordUpdateController::class)->group(function (){
        Route::post('new-password', 'createNewPassword');
        Route::post('update-password', 'updatePassword');
        Route::post('upload-nida', 'nidaNumberUpdate');
    });

    Route::controller(userDeclarationController::class)->group(function (){
        Route::get('declarations', 'declarations');
        Route::get('declarationsCheck', 'declarationsCheck');
        Route::get('declarationsCheckNyongeza', 'declarationsCheckNyongeza');
        Route::get('declaration/form/{secure_token?}', 'declarationForm');
        Route::get('declaration/sections/form/{secure_token?}', 'sectionRequirementsForm');
        Route::post('save/declaration', 'declarationSave');
        Route::post('update/declaration/section/{id?}', 'updateSection');
        Route::post('submit/declaration', 'declarationSubmission');
        Route::post('declaration/preview', 'previewAdf');
        Route::post('declaration/confirmation', 'confirmDeclarationPreview');
        Route::post('delete/declaration', 'deleteDeclaration');
        Route::post('declaration/receipt', 'getDeclarationReceipt');
        Route::post('declaration/download', 'downloadAdf');
        Route::get('declaration/history', 'ADFDownloadHistory');
        Route::post('declaration/user-declaration/create', 'DeclarationCreate');
        Route::post('declaration/createNyogezaPunguzoDeclaration', 'DeclarationCreateNyongezaPunguzo');
        Route::get('declaration/family-member/{user_declaration_id?}', 'familyMemberDeclaration');
        Route::post('declaration/declaration-sections-requirements', 'declarationSectionsRequirements');
        Route::post('declaration/section-data-delete', 'sectionDataDelete');
        Route::post('declaration/getSectionsList', 'getSectionsList');
        Route::get('declaration/ADFSubmittedList', 'ADFSubmittedList');
        Route::post('declaration/updateSectionData', 'updateSectionData');
        Route::post('declaration/apply-integrity-pledge', 'integrityPledge');
        
    });

    Route::controller(familyMemberController::class)->group(function (){
        Route::post('register/family-member', 'addFamilyMember');
        Route::get('family-members', 'getFamilyMembers');
        Route::get('edit/family-member/{id?}', 'editFamilyMember');
        Route::post('update/family-member/{id?}', 'updateFamilyMember');
	Route::delete('delete/family-member/{token?}', 'deleteFamilyMember');
    });

 Route::controller(KiongoziController::class)->group(function (){
	    Route::get('ajira-list', 'getTaarifaAjira');
	    Route::get('edit-taarifa/{token?}', 'editTaarifaAjira');
        Route::post('taarifa-ajira', 'ajiraTaarifa');
        Route::post('badili-taarifaAjira/{token?}', 'updateAjiraTaarifa');
     });

  Route::controller(BinafsiController::class)->group(function (){
	   Route::get('edit-user/{id?}', 'edit');
        Route::post('update-user/{id?}', 'update');
        Route::post('taarifa-binafsi', 'nida');
        Route::get('taarifa', 'getUser');
    });

  Route::controller(rejestaZawadiController::class)->group(function () {
    Route::get('rejesta-list', 'index');
    Route::post('add-rejesta', 'store');
    Route::get('view-rejesta', 'view');
    Route::get('edit-rejesta/{rejesta_id?}', 'edit');
    Route::post('update-rejesta/{rejesta_id?}', 'update');
    Route::get('delete-rejesta/{rejesta_id?}', 'delete');
  });


    Route::controller(lookUpDataController::class)->group(function () {
    Route::get('family-member-type', 'familyMemberType');
    Route::get('menuLookup', 'menuLookup');
    Route::get('leadersList', 'leadersList');
    Route::post('freeze_data', 'freeze_data');
    Route::get('countries', 'country');
    Route::get('regions', 'regions');
    Route::get('districts/{regionId?}', 'districts');
    Route::get('wards/{LgaCode?}', 'wards');
    Route::get('sex', 'sex');
    Route::get('marital-statuses', 'maritalStatus');
    Route::get('building-type', 'buildingType');
    Route::get('titles', 'titles');
    Route::get('offices', 'offices');
    Route::get('employment-type', 'employmentType');
    Route::get('declaration-type', 'declarationType');
    Route::get('type-of-use', 'typeOfUse');
    Route::get('source-of-income', 'sourceOfIncome');
    Route::get('property-type', 'propertyType');
    Route::get('transport-types', 'transportTypes');
    Route::get('debt-types', 'debtTypes');
    Route::get('uuid', 'uuid');
    Route::get('hadhi', 'hadhi');
    Route::get('financial_year', 'financial_year');
    Route::get('councils/{district_id?}', 'councils');
    Route::get('villages/{ward_id?}', 'villages');
    Route::get('get_selected_date', 'get_selected_date');
    
});

});

// Route::controller(lookUpDataController::class)->group(function () {
//     Route::get('countries', 'country');
//     Route::get('regions', 'regions');
//     Route::get('districts/{regionId?}', 'districts');
//     Route::get('wards/{LgaCode?}', 'wards');
//     Route::get('sex', 'sex');
//     Route::get('marital-statuses', 'maritalStatus');
//     Route::get('building-type', 'buildingType');
//     Route::get('titles', 'titles');
//     Route::get('offices', 'offices');
//     Route::get('employment-type', 'employmentType');
//     Route::get('declaration-type', 'declarationType');
//     Route::get('type-of-use', 'typeOfUse');
//     Route::get('source-of-income', 'sourceOfIncome');
//     Route::get('property-type', 'propertyType');
//     Route::get('transport-types', 'transportTypes');
//     Route::get('debt-types', 'debtTypes');
//     Route::get('uuid', 'uuid');
//     Route::get('hadhi', 'hadhi');
//     Route::get('financial_year', 'financial_year');
//     Route::get('councils/{district_id?}', 'councils');
//     Route::get('villages/{ward_id?}', 'villages');
// });

Route::controller(notificationController::class)->group(function () {
    Route::get('notifications', 'showNotifications');
 Route::get('contacts', 'contacts');
    Route::get('instructions', 'instructions');
});
