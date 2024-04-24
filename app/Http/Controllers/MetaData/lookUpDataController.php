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
use App\Models\Hadhi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Financial_year;
use App\Models\Council;
use App\Models\Village;
use App\Models\Menu_lookup;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Sectiontaarafa478;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class lookUpDataController extends Controller
{
    // public function get_api($endpoint,$values = ''){
    public function get_api($end_point, $value = null){

        if($value){
            $URL  = 'http://api.maadili.go.tz:9003/emis/'.$end_point.'/'.$value;
        }else{
            $URL  = 'http://api.maadili.go.tz:9003/emis/'.$end_point;
        }
        try{
        $result  =  Http::get($URL);
        $result = json_decode($result);
        return response()->json($result);

        }catch (Exception $error) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'something went wrong.',
                'error' => $error,
            ]);
        }
    }

    public function listApprovedIntegrity(Request $request){

        $URL  = externalURL().'listApprovedIntegrity';
        try{

        $data = $request->all();
        $result = Http::post($URL, $data);
        $result = json_decode($result);
        return response()->json($result);

        }catch (Exception $error) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'something went wrong.',
                'error' => $error,
            ]);
        }
    }

    public function MyListIntegrityPledge(Request $request){

        $URL  = externalURL().'MyListIntegrityPledge';
        try{
        $data = $request->all();
        $result = Http::post($URL, $data);
        $result = json_decode($result);
        return response()->json($result);

        }catch (Exception $error) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'something went wrong.',
                'error' => $error,
            ]);
        }
    }

    public function applyIntegrity(Request $request){



        $URL  = externalURL().'apply-integrity';
        try{
        $data = $request->all();
        $result = Http::post($URL, $data);
        Log::info('applyIntegrity - '.$result);
        $result = json_decode($result);


        return response()->json($result);

        }catch (Exception $error) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'something went wrong.',
                'error' => $error,
            ]);
        }
    }

    public function NIDAVerifier(Request $request){

        $URL  = nidaURL().'NIDA-Verifier';
        try{
        $data = [
            "NIN" => $request->NIN,
            "ANSWER" => $request->ANSWER ?? '',
            "RQCODE" => $request->RQCODE ?? ''
        ];
        $result = Http::post($URL, $data);
        $result = json_decode($result);
        return response()->json($result);

        }catch (Exception $error) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'something went wrong.',
                'error' => $error->getMessage(),
            ]);
        }
    }

    public function updateUser(Request $request){

        $URL  = externalURL().'updateUser';
        try{
        $data = $request->all();
        $result = Http::post($URL, $data);
        $result = json_decode($result);

        return response()->json($result);

        }catch (Exception $error) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'something went wrong.',
                'error' => $error,
            ]);
        }
    }

    public function get_selected_date(){
            $kiongozi = Sectiontaarafa478::where('user_id', auth()->user()->id)->latest('created_at')->first();
                if($kiongozi){
                    $selected_date = $kiongozi->selected_date;
                    $message = 'Tarehe ya kuchaguliwa';
                    $statusCode = '200';
                }else{
                    $selected_date = null;
                    $message = 'Hakuna Taarifa Za ajila';
                    $statusCode = '500';

                }


        $response = ['selected_date' => $selected_date, 'message' => $message, 'statusCode' =>$statusCode ];

        return response()->json($response,200);

    }

    public function freeze_data(Request $request){

        $validator = Validator::make($request->all(), [
            'section_id' => 'required|integer',
            'freeze_type_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'validation error',
                'fields' => $validator->errors(),
                'error' => true,
            ]);
        }


        $freeze_datas = DB::table('section_requirements')
                        ->join('requirements','requirements.id','=','section_requirements.requirement_id')
                        ->where('section_requirements.section_id', '=', $request->section_id)
                        ->where('section_requirements.freeze_type', '=', '0')
                        ->where('section_requirements.freeze_type_id', '=', $request->freeze_type_id)
                        ->get();


        $response = ['freeze_datas' => $freeze_datas];

        return response()->json($response,200);

    }

    public function financial_year(){
        $Financial_year = Financial_year::where('is_active', '=', true)->get();

        $response = ['Financial_year' => $Financial_year];

        return response()->json($response,200);

    }

    public function leadersList(){
        $leadersList = User::where('is_active', '=', true)
                ->selectRaw('id, CONCAT(first_name, \' \', COALESCE(middle_name, \'\'), \' \', last_name) AS full_name, phone_number, sex')
                ->get();


        $response = ['leadersList' => $leadersList];

        return response()->json($response,200);

    }

    public function hadhi(){


        $hadhi = Hadhi::get();

        $response = ['hadhi' => $hadhi];

        return response()->json($response,200);

    }

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

    public function districts($regionId): JsonResponse
    {
        $districts = District::where('region_id','=',$regionId)->get();
        $response = ['districts' => $districts];
        return response()->json($response,200);
    }

    public function wards($LgaCode): JsonResponse
    {

        $wards = Ward::where('district_id','=',$LgaCode)->get();

        $response = ['wards' => $wards];

        return response()->json($response,200);
    }

    public function villages($ward_id): JsonResponse
    {

        $villages = Village::where('ward_id','=',$ward_id)->get();

        $response = ['villages' => $villages];

        return response()->json($response,200);
    }

    public function councils($district_id): JsonResponse
    {

        $councils = Council::where('district_id','=',$district_id)->get();

        $response = ['councils' => $councils];

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

    public function menuLookup(){
        $menu_lookup = Menu_lookup::where('user_id','=',auth()->user()->id)->first();

        if($menu_lookup){

            if($menu_lookup->stage_one === false){
                return response()->json([
                    'statusCode' => 400,
                    'message' => 'Ndugu kiongozi tafadhali jaza kwanza taarifa binafsi ili uweze kuendelea.',
                    'error' => false,
                ]);
            }

            if($menu_lookup->stage_two === false){
                $user = User::where('id',auth()->user()->id)->first();
                if ($user !== null && ($user->maritial_status_id === 2 || $user->maritial_status_id === 3)) {

                return response()->json([
                    'statusCode' => 401,
                    'message' => 'Ndugu kiongozi tafadhali jaza kwanza taarifa za wategemezi ili uweze kuendelea.',
                    'error' => false,
                ]);
               }
            }

            if($menu_lookup->stage_three === false){
                return response()->json([
                    'statusCode' => 402,
                    'message' => 'Ndugu kiongozi tafadhali jaza kwanza taarifa za ajira ili uweze kuendelea.',
                    'error' => false,
                ]);
            }


        }


    }

    public function declarationType(): JsonResponse
    {

        $declaration_types = Declaration_type::where('is_available','=',true)->get();

        $response = ['declaration_types' => $declaration_types];

        return response()->json($response,200);
    }

    public function familyMemberType(): JsonResponse
    {

        $sex = auth()->user();
        // dd($sex);
        if($sex){
            if($sex->sex_id == '1'){
                $member_sw = 'Mume';

                if($sex->marital_status_id == '4'){
                    $removeNimeoa = 'Mke';
                }else{
                    $removeNimeoa = '';
                }
            }elseif($sex->sex_id == '2'){
                $member_sw = 'Mke';

                if($sex->marital_status_id == '5'){
                    $removeNimeoa = 'Mume';
                }else{
                    $removeNimeoa = '';
                }

            }else{
                return response()->json([
                    'statusCode' => 500,
                    'message' => 'Ndugu kiongozi tafadhali sasisha kwanza taarifa binafsi ili uweze kuendelea.',
                    'error' => false,
                ]);

            }
        }else{
            return response()->json([
                'statusCode' => 500,
                'message' => 'Ndugu kiongozi tafadhali sasisha kwanza taarifa binafsi ili uweze kuendelea.',
                'error' => false,
            ]);

        }
        // $member_types = Family_member_type::get();
        $member_types = Family_member_type::whereNotIn('member_sw', [$member_sw,$removeNimeoa])->get();
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

    public function uuid()
    {

        return Str::uuid();
    }
}
