<?php

namespace App\Http\Controllers\MetaData;

use App\Http\Controllers\Controller;
use App\Models\Building_type;
use App\Models\Country;
use App\Models\Debt_type;
use App\Models\Declaration_type;
use App\Models\District;
use App\Models\Employment_type;
use App\Models\Family_member_type;
use App\Models\Marital_status;
use App\Models\Office;
use App\Models\Property_type;
use App\Models\Region;
use App\Models\Sex;
use App\Models\Source_of_income;
use App\Models\Title;
use App\Models\Transportation_type;
use App\Models\Type_of_use;
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

    public function sex(): JsonResponse
    {

        $sex = Sex::get();

        $response = ['sex' => $sex];

        return response()->json($response,200);
    }

    public function maritalStatus(): JsonResponse
    {

        $marital = Marital_status::get();

        $response = ['marital' => $marital];

        return response()->json($response,200);
    }

    public function buildingType(): JsonResponse
    {

        $building_types = Building_type::get();


        $response = ['building_types' => $building_types];

        return response()->json($response,200);
    }

    public function titles(): JsonResponse
    {

        $titles = Title::get();

        $response = ['titles' => $titles];

        return response()->json($response,200);
    }

    public function offices(): JsonResponse
    {

        $offices = Office::get();

        $response = ['offices' => $offices];

        return response()->json($response,200);
    }

    public function employmentType(): JsonResponse
    {

        $employment_types = Employment_type::get();

        $response = ['employment_types' => $employment_types];

        return response()->json($response,200);
    }

    public function declarationType(): JsonResponse
    {

        $declaration_types = Declaration_type::where('is_available','=',true)->get();

        $response = ['declaration_types' => $declaration_types];

        return response()->json($response,200);
    }

    public function familyMemberType(): JsonResponse
    {

        $member_types = Family_member_type::get();

        $response = ['member_types' => $member_types];

        return response()->json($response,200);
    }

    public function typeOfUse(): JsonResponse
    {
        $types = Type_of_use::get();

        $response = ['types' => $types];

        return response()->json($response,200);
    }

    public function sourceOfIncome(): JsonResponse
    {
        $incomes = Source_of_income::get();

        $response = ['incomes' => $incomes];

        return response()->json($response,200);
    }

    public function propertyType(): JsonResponse
    {
        $property_types = Property_type::get();

        $response = ['property_types' => $property_types];

        return response()->json($response,200);
    }

    public function transportTypes(): JsonResponse
    {
        $transport_types = Transportation_type::get();

        $response = ['transport_types' => $transport_types];

        return response()->json($response,200);
    }

    public function debtTypes(): JsonResponse
    {
        $debt_types = Debt_type::get();

        $response = ['debt_types' => $debt_types];

        return response()->json($response,200);
    }
}
