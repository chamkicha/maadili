<?php

namespace App\Http\Controllers\MetaData;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\District;
use App\Models\Region;
use App\Models\Ward;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class lookUpDataController extends Controller
{

    public function country(): JsonResponse
    {

        $countries = Country::get();

        $response = ['countries' => $countries];

        return response()->json($response,200);
    }
    public function regions(): JsonResponse
    {

        $regions = Region::get();

        $response = ['regions' => $regions];

        return response()->json($response,200);
    }

    public function districts($RegionCode): JsonResponse
    {

        $districts = District::where('RegionCode','=',$RegionCode)->get();

        $response = ['districts' => $districts];

        return response()->json($response,200);
    }

    public function wards($LgaCode): JsonResponse
    {

        $wards = Ward::where('LgaCode','=',$LgaCode)->get();

        $response = ['wards' => $wards];

        return response()->json($response,200);
    }
}
