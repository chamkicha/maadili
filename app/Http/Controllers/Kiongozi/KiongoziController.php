<?php

namespace App\Http\Controllers\Kiongozi;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Sectiontaarafa478;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Zone;
use App\Models\Office;
use App\Models\Title;
use DB;


class KiongoziController extends Controller
{
    public function getTaarifaAjira()
    {
            $kiongozi = Sectiontaarafa478::with('mwaajiri')->with('country')->with('kata_sasa_name')->with('title_name')->with('wilaya_sasa_name')->with('mkoa_sasa_name')
                       ->with('userDeclaration')->with('councils')->with('village')->with('ainaya_ajira')->where('user_id','=',auth()->user()->id)->orderByDesc('id')->get();
            $response =  ['statusCode' => 200, 'taarifa_za_ajira' => $kiongozi ];
            return response()->json($response);

    }

    public function ajiraTaarifa(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'date_employment' => 'required|string',
            'posh' => 'required|string',
            'other_revenue' => 'required|string',
            'last_title' => 'required|string',
            'last_date_employment' => 'required|string',
            'last_end_title_date' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

     $latestData = Sectiontaarafa478::where('user_id','=',auth()->user()->id)->latest()->first();
        if ($latestData) {
            $latestData->is_active = false;
            $latestData->save();
        }

   $user = User::where('id','=',auth()->user()->id)->first();

        $isactive=true;
   $kiongozi = Sectiontaarafa478::updateOrCreate([
    'secure_token' => Str::uuid(),
    'user_id' => auth()->user()->id,
    'title_id' => $request->input('title_id'),
    'date_employment' => $request->input('date_employment'),
    'type_employment' => $request->input('type_employment'),
    'salary' => $request->input('salary'),
    'posh' => $request->input('posh'),
    'other_revenue' => $request->input('other_revenue'),
    'employer' => $request->input('employer'),
    'last_title' => $request->input('last_title'),
    'selected_date' => $request->input('selected_date'),
    'last_employer' => $request->input('last_employer'),
    'kuthibitishwa_date' => $request->input('kuthibitishwa_date'),
    'mkoa_sasa' => $request->input('mkoa_sasa'),
    'wilaya_sasa' => $request->input('wilaya_sasa'),
    'kata_sasa' => $request->input('kata_sasa'),
    'councils_id' => $request->input('councils_id'),
    'village_id' => $request->input('village_id'),
    'last_date_employment' => $request->input('last_date_employment'),
    'last_end_title_date' => $request->input('last_end_title_date'),
    'country_id' => $request->input('country_id'),
    'physical_address' => $request->input('physical_address'),
    'maelezo_ya_cheo_wadhifa' => $request->input('maelezo_ya_cheo_wadhifa'),
    'is_active' => $isactive
]);
 if( $kiongozi){
    $user->title_id=$kiongozi ->title_id;
    $user->update();
    }

    $createMenuLookup = createMenuLookup('stage_three');

$response =  ['statusCode' => 200, 'message' => 'Umefanikiwa kusajili taarifa zako za Ajira'];
return response()->json($response);
    }

    public function editTaarifaAjira($token)
    {
        $kiongozi = Sectiontaarafa478::where('secure_token','=',$token)->first();
        $response =  ['statusCode' => 200, 'ajira' => $kiongozi ];
        return response()->json($response);
    }
    public function updateAjiraTaarifa(Request $request ,$token)
    {
        $validator = Validator::make($request->all(),[
            'date_employment' => 'required|string',
            'posh' => 'required|string',
            // 'other_revenue' => 'required|string',
            'last_title' => 'required|string',
            'last_date_employment' => 'required|string',
            'last_end_title_date' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
    $user = User::where('id','=',auth()->user()->id)->first();


    $file_number = $this->file_number($request);

        $isactive=true;
       $kiongozi = Sectiontaarafa478::where('secure_token','=',$token)->first();
       $kiongozi ->user_id = auth()->user()->id;
       $kiongozi ->title_id = $request->input('title_id');
       $kiongozi ->date_employment = $request->input('date_employment');
       $kiongozi ->type_employment = $request->input('type_employment');
       $kiongozi ->salary = $request->input('salary');
       $kiongozi ->posh = $request->input('posh');
       $kiongozi ->other_revenue = $request->input('other_revenue');
       $kiongozi ->employer = $request->input('employer');
       $kiongozi ->last_title = $request->input('last_title');
       $kiongozi ->selected_date = $request->input('selected_date');
       $kiongozi ->last_employer = $request->input('last_employer');
       $kiongozi ->kuthibitishwa_date = $request->input('kuthibitishwa_date');
       $kiongozi ->mkoa_sasa = $request->input('mkoa_sasa');
       $kiongozi ->wilaya_sasa = $request->input('wilaya_sasa');
       $kiongozi ->kata_sasa = $request->input('kata_sasa');
       $kiongozi ->councils_id = $request->input('councils_id');
       $kiongozi ->village_id = $request->input('village_id');
       $kiongozi ->country_id = $request->input('country_id');
       $kiongozi ->physical_address = $request->input('physical_address');
       $kiongozi ->last_date_employment = $request->input('last_date_employment');
       $kiongozi ->last_end_title_date = $request->input('last_end_title_date');
       $kiongozi ->maelezo_ya_cheo_wadhifa = $request->input('maelezo_ya_cheo_wadhifa');
       $kiongozi ->is_active = $request->input('is_active');
       if( $kiongozi->save()){
            $user->file_number=$file_number;
            $user->title_id=$kiongozi ->title_id;
            $user->update();
       }

       $createMenuLookup = createMenuLookup('stage_three');

       $response =  ['statusCode' => 200, 'message' => 'Umefanikiwa kurekebisha taarifa zako za Ajira'];
       return response()->json($response);
    }

    public function file_number($request){

        $user = User::where('id','=',auth()->user()->id)->first();
        $kanda = Zone::join('regions','regions.zone_id','=','zones.id')
                       ->where('regions.id',$request->mkoa_sasa)
                      ->first();
        if($kanda){
         $kanda = $kanda->abbreviation;
        }else{
         $kanda = 'HQ';
        }

        $taasisi = Office::where('id',$request->employer)->first();
        if($taasisi){
            $taasisi = $taasisi->abbreviation;

        }else{
            $taasisi ='PPRA';
        }
        $cheo = Title::where('id',$request->title_id)->first();
        if($cheo){
            $cheo = $cheo->abbreviation;

        }else{
            $cheo ='MNGR';
        }

        $CPF = 'CPF';

        $file_number = 'ES'.'/'.$kanda.'/'.$CPF.'/'.$taasisi.'/'.$cheo;

        $results = DB::table('users')->where('file_number', 'LIKE', $file_number.'%')->select('id','file_number')->get();

        if($results){
            $namba_array = [];
            foreach($results as $result){
                $lastTwoDigits = substr($result->file_number, -2);
                $namba_array[] =$lastTwoDigits;
            }

            sort($namba_array);
            $lastValue = intval(end($namba_array));

            $namba = $lastValue + 1;
            $namba = str_pad($namba, 2, '0', STR_PAD_LEFT);

        }else{
        $namba = '01';

        }

        $file_number = 'ES'.'/'.$kanda.'/'.$CPF.'/'.$taasisi.'/'.$cheo.'/'.$namba;

        return $file_number;
    }
}

