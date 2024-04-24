<?php

use App\Models\Menu_lookup;
use App\Models\User_declaration;
use App\Models\Declaration_type;
use App\Models\Section;
use App\Models\Sectiontaarafa478;
use App\Models\User;
use App\Http\Controllers\Kiongozi\KiongoziController;
use App\Models\Zone;
use App\Models\Office;
use App\Models\Title;

if (!function_exists('externalURL')) {
    function externalURL(){

        $environment = env('APP_ENV');

        if ($environment === 'local') {
            $URL = 'http://41.59.227.219:8089/api/';

        } else {
            $URL = 'http://api.maadili.go.tz:9003/';

        }

        return $URL;
    }
}

if (!function_exists('nidaURL')) {
    function nidaURL(){

        $URL = 'http://10.20.62.6:8089/api/';
        // $URL = 'http://41.59.227.219:8089/api/';
        // $URL = 'http://api.maadili.go.tz:9003/';

        return $URL;
    }
}


if (!function_exists('file_number')) {
    function file_number($request){

    $user = User::where('id','=',$request->user_id)->first();
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

    $file_number = 'ES'.'/'.$kanda.'/'.$CPF.$taasisi.'/'.$cheo;

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

    $file_number = 'ES'.'/'.$kanda.'/'.$CPF.$taasisi.'/'.$cheo.'/'.$namba;

    return $file_number;
}

}



if (!function_exists('createMenuLookup')) {
    function createMenuLookup($stage)
    {

        $menu_lookup = Menu_lookup::where('user_id','=',auth()->user()->id)->first();

        if($menu_lookup){
            $menu_lookup->{$stage} = true;
            $menu_lookup->save();
        }else{

        $data = [];
        $data['user_id']=auth()->user()->id;
        $data[$stage]= true;
        $announcement = Menu_lookup::create($data);

        }

    }


}

if (!function_exists('sectioncountAll')) {
    function sectioncountAll($user_declaration_id)
    {

       $user_declaration = User_declaration::where('id', $user_declaration_id)->first();

        if($user_declaration)
        {

            $sections = Declaration_type::join('declaration_sections','declaration_sections.declaration_type_id','=','declaration_types.id')
                                            ->join('sections','sections.id','=','declaration_sections.section_id')
                                            ->where('declaration_types.id',$user_declaration->declaration_type_id)
                                            ->where('sections.status_id','1')
                                            ->get();

            $total_data_count = count($sections);
            if($total_data_count){
                return $total_data_count;

            }else{
                return 0;
            }

        }

    }


}

if (!function_exists('sectioncount')) {
    function sectioncount($user_declaration_id,$user_id,$is_pl)
    {

       $user_declaration = User_declaration::where('id', $user_declaration_id)->first();

        if($user_declaration)
        {

            $sections = Declaration_type::join('declaration_sections','declaration_sections.declaration_type_id','=','declaration_types.id')
                                            ->join('sections','sections.id','=','declaration_sections.section_id')
                                            ->where('declaration_types.id',$user_declaration->declaration_type_id)
                                            ->get();
            $count_section_data = [];

            foreach ($sections as $section) {
                $table_name = strtolower($section->table_name);

                $section_datas = DB::table($table_name)
                    ->where('user_declaration_id', $user_declaration->id)
                    ->where('member_id', $user_id)
                    ->where('is_pl', $is_pl)
                    ->where('is_deleted','1')
                    ->first();

                    if($section_datas) {
                        $count_section_data[] = 1;
                    }
            }

            $total_data_count = count($count_section_data);
            if($total_data_count){
                return $total_data_count;

            }else{
                return 0;
            }


        }

    }


}


if (!function_exists('menuLookupCheck')) {
 function menuLookupCheck(){
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


    }else{

        return response()->json([
            'statusCode' => 400,
            'message' => 'Ndugu kiongozi tafadhali jaza kwanza taarifa binafsi ili uweze kuendelea.',
            'error' => false,
        ]);
    }


}
}
