<?php

namespace App\Http\Controllers\Kiongozi;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class BinafsiController extends Controller
{
    public function getUser()
    {
         $kiongozi = User::with('zone')->with('marital')->with('hadhi')->with('councils')->with('village')
        ->with('sex')->with('countryCurrentInfo')->with('countryBirthInfo')
        ->with('districtCurrentInfo')->with('regionCurrentInfo')->with('wardCurrentInfo')
        ->where('id','=',auth()->user()->id)->first();
        $response =  ['statusCode' => 200, 'kiongozi' => $kiongozi ];
        return response()->json($response);
}

      public function edit($id)
    {
        $kiongozi = User::find($id);
        $response =  ['statusCode' => 200, 'kiongozi' => $kiongozi ];
        return response()->json($response);
    }

    public function update(Request $request ,$id)
    {

        $kiongozi = User::find($id);
        $kiongozi ->first_name = $request->input('first_name');
        $kiongozi ->middle_name = $request->input('middle_name');
        $kiongozi ->last_name = $request->input('last_name');
        $kiongozi ->phone_number2 = $request->input('phone_number2');
        $kiongozi ->email = $request->input('email');
        $kiongozi ->nationality = $request->input('nationality');
        $kiongozi ->po_box = $request->input('po_box');
        $kiongozi ->sex = $request->input('sex');
 	    $kiongozi ->aka = $request->input('aka');
        $kiongozi ->date_of_birth = $request->input('date_of_birth');
        $kiongozi ->house_no = $request->input('house_no');
        $kiongozi ->tin_number = $request->input('tin_number');
        $kiongozi ->ward_nida = $request->input('ward_nida');
        $kiongozi ->councils_id = $request->input('councils_id');
        $kiongozi ->village_id = $request->input('village_id');
        $kiongozi ->region_nida = $request->input('region_nida');
        $kiongozi ->district_nida = $request->input('district_nida');
 	    $kiongozi ->title_id = $request->input('title_id');
 	    $kiongozi ->hadhi_id = $request->input('hadhi_id');
        $kiongozi ->village_nida = $request->input('village_nida');
        $kiongozi ->passport = $request->input('passport');
        $kiongozi ->profile_picture = $request->input('profile_picture');
        $kiongozi ->signature_image = $request->input('signature_image');
        
        $kiongozi ->sex_id = $request->input('sex_id');
        $kiongozi ->marital_status_id = $request->input('marital_status_id');
        $kiongozi ->check_number = $request->input('check_number');
        $kiongozi ->country_birth = $request->input('country_birth');
        $kiongozi ->country_current = $request->input('country_current');
        $kiongozi ->ward_current = $request->input('ward_current');
        $kiongozi ->district_current = $request->input('district_current');
        $kiongozi ->region_current = $request->input('region_current');
        $kiongozi ->po_box_current = $request->input('po_box_current');
        
        $kiongozi ->physical_address_current = $request->input('physical_address_current');
        $kiongozi ->country_current = $request->input('country_current');
        $kiongozi ->kijiji_mtaa_shehia = $request->input('kijiji_mtaa_shehia');
        $kiongozi ->kijiji_mtaa_shehia_current = $request->input('kijiji_mtaa_shehia_current');
        $kiongozi ->village_string = $request->input('village_string');
        $kiongozi->save();
        $createMenuLookup = createMenuLookup('stage_one');

        $response =  ['statusCode' => 200, 'message' => 'Umefanikiwa kurekebisha taarifa zako binafsi'];
        return response()->json($response);
    }


    public function nida(Request $request)
    {
       $kiongozi = User::find(Auth::id());
       $kiongozi ->first_name = $request->input('first_name');
       $kiongozi ->middle_name = $request->input('middle_name');
       $kiongozi ->last_name = $request->input('last_name');
       $kiongozi ->phone_number2 = $request->input('phone_number2');
       $kiongozi ->email = $request->input('email');
	  $kiongozi ->nida = $request->input('nida');
       $kiongozi ->nationality = $request->input('nationality');
       $kiongozi ->po_box = $request->input('po_box');
       $kiongozi ->sex = $request->input('sex');
       $kiongozi ->date_of_birth = $request->input('date_of_birth');
       $kiongozi ->house_no = $request->input('house_no');
       $kiongozi ->tin_number = $request->input('tin_number');
       $kiongozi ->ward_nida = $request->input('ward_nida');
       $kiongozi ->region_nida = $request->input('region_nida');
       $kiongozi ->district_nida = $request->input('district_nida');
       $kiongozi ->village_nida = $request->input('village_nida');
       $kiongozi ->passport = $request->input('passport');
       $kiongozi ->profile_picture = $request->input('profile_picture');
       $kiongozi ->zone_id = $request->input('zone_id');
      $kiongozi ->signature_image = $request->input('signature_image');
       $kiongozi->save();
       $response =  ['statusCode' => 200, 'message' => 'Umefanikiwa kurekebisha taarifa zako binafsi'];
       return response()->json($response);
    }
}

