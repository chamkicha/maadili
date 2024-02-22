<?php

namespace App\Http\Controllers\Declaration;

use App\Http\Controllers\Controller;
use App\Models\Asset_declaration_window;
use App\Models\Declaration_download;
use App\Models\Declaration_section;
use App\Models\Declaration_type;
use App\Models\Financial_year;
use App\Models\Section;
use App\Models\Section_requirement;
use App\Models\User;
use App\Models\UserDeclarationsLookup;
use App\Models\User_declaration;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use stdClass;
use App\Models\Family_member;
use App\Models\Sectiontaarafa478;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use App\Models\Menu_lookup;
use Illuminate\Support\Facades\Log;
use App\Models\integrity_pledge;
use Illuminate\Support\Facades\File;

class userDeclarationController extends Controller
{
    public function declarationsCheck(){
        try{

        $year = Financial_year::where('is_active', '=', true)->first();

        // $check = User_declaration::where('user_id', '=', auth()->user()->id)
        //         ->with('declaration_type')
        //         ->where('financial_year_id', '=', $year->id)
        //         ->where('flag', '=', 'save')
        //         ->where('is_deleted', '=', false)
        //         ->first();

        $check = User_declaration::where('user_id', '=', auth()->user()->id)
                    ->with('declaration_type')
                    ->where('financial_year_id', '=', $year->id)
                    ->where('is_deleted', '=', false)
                    ->where('flag', '=', 'save')
                    ->orderBy('id', 'desc') // Sorting in descending order using the 'id' field
                    ->first();


                if($check){


                    $declaration = Declaration_type::where('id',$check->declaration_type_id)->first();
                        if($declaration){
                            $declaration_type_token = $declaration->secure_token;
                            $declaration_model = $declaration->declaration_model;
                        }else{
                            $declaration_type_token = null;
                        }


                    $response = [
                                 'statusCode' => 200,
                                 'message' => 'Ndugu kiongozi bado unaendelea kujaza '.$check->declaration_type->type.' , Tafadhali malizia kujaza, Ahsante!.',
                                 'message_thibitisha' => 'Tafadhali bonyeza "THIBITISHA" kama hauna matamko katika maeneo hayo, AU bonyeza "ENDELEA" ili kuendelea kujaza. Ahsante',
                                 'user_declaration_id' => $check->id,
                                 'declaration_type_token' => $declaration_type_token,
                                 'declaration_model' => $declaration_model,
                                 'declaration_type_id' => $check->declaration_type_id,
                                 'is_nyongeza' => $check->is_nyongeza
                                ];

                    return response()->json($response, 200);
                }else{

                    $response = [
                        'statusCode' => 201,
                        'message' => 'Samahani Ndugu Kiongozi, Hauna tamko la kuthibitisha.',
                       ];

                    return response()->json($response, 200);
                }

        } catch (Exception $error) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'Something went wrong.',
                'error' => $error,
            ]);
        }
    }


    public function declarationsCheckNyongeza(){
        try{

        $year = Financial_year::where('is_active', '=', true)->first();

        $check = User_declaration::where('user_id', '=', auth()->user()->id)
                ->with('declaration_type')
                ->where('financial_year_id', '=', $year->id)
                ->where('flag', '=', 'submit')
                ->where('is_deleted', '=', false)
                ->first();

                if($check){



                $declaration = Declaration_type::where('id',$check->declaration_type_id)->first();
                if($declaration){
                    $declaration_type_token = $declaration->secure_token;
                    $declaration_model = $declaration->declaration_model;
                }else{
                    $declaration_type_token = null;
                }


                    $response = [
                                 'statusCode' => 200,
                                 'message' => 'Ndugu kiongozi endelea kuongeza au kupunguza '.$check->declaration_type->type.' , Ahsante!.',
                                 'user_declaration_id' => $check->id,
                                 'declaration_type_token' => $declaration_type_token,
                                 'declaration_model' => $declaration_model,
                                 'declaration_type_id' => $check->declaration_type_id,
                                ];

                    return response()->json($response, 200);
                }else{

                    $response = [
                        'statusCode' => 201,
                        'message' => 'Samahani Ndugu Kiongozi, Hauna tamko la Kuongeza au Kupunguza.',
                       ];

                    return response()->json($response, 200);
                }

        } catch (Exception $error) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'Something went wrong.',
                'error' => $error,
            ]);
        }
    }


    public function declarations(): JsonResponse
    {

        $declaration_window = Asset_declaration_window::with([
            'declarations' => function ($query) {
                $query->where('status_id','=', '1');
                $query->select('id', 'secure_token', 'type','declaration_model');
            }
        ])
            ->where('is_active', '=', true)
            ->select('id', 'declaration_type_id', 'is_active')
            ->get();

      //        $declarations = Declaration_type::get();

        $response = ['statusCode' => 200, 'declaration_window' => $declaration_window];

        return response()->json($response, 200);

    }

    public function declarationForm($secure_token): JsonResponse
    {

        $today = Carbon::now();

        $year = Financial_year::where('is_active', '=', true)->first();

         $declaration = Declaration_type::with([
             'sections' => function ($query) {
            $query->orderBy('declaration_sections.section_flow', 'ASC')->where('status_id',1);
        }
        ])
            ->where('secure_token', '=', $secure_token)
            ->first();




        foreach ($declaration->sections as $section) {
            $table_name = strtolower($section->table_name);

            $check_user_dec = DB::table($table_name)
                            ->where('is_deleted','1')
                            //   ->where()
                              ->first();

            // Check if $check_user_dec has data
            $section->has_data = $check_user_dec !== null ? 1 : 0;
        }

        $check = User_declaration::where('user_id', '=', auth()->user()->id)
            ->where('financial_year_id', '=', $year->id)
            ->where('declaration_type_id', '=', $declaration->id)
            ->first();

        // if ($check != null) {
        //     if ($check->is_confirmed && $declaration->declaration_code == "TRM") {

        //         $response = ['statusCode' => 400, 'message' => 'Tayari umeshathibitisha kutuma tamko hili, kwahyo uwezi kujaza tena', 'data' => $check];

        //         return response()->json($response);
        //     } elseif ($check->is_confirmed && $declaration->declaration_code != "TRM") {

        //         $initDay = Carbon::parse($check->created_at);

        //         $diffDays = $initDay->diffInDays($today);

        //         if ($diffDays <= 7) {

        //             $response = ['statusCode' => 400, 'message' => 'Uwezi kujaza tamko ili kulingana na mda uliotumia awali kujaza aina hii ya tamko,tafadhali subiri zipite siku 7 ndo uweze kujaza tena', 'data' => $check];

        //             return response()->json($response);
        //         }

        //     }
        // }
        $response = ['statusCode' => 200,'declaration' => $declaration];

        return response()->json($response, 200);
    }

    public function getSectionsList(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'declaration_type_secure_token' =>  ['required','string'],
            'member_id' =>  ['required','integer'],
            'is_pl' =>  ['required','integer'],
            'user_declaration_id' =>  ['required','string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'validation error',
                'fields' => $validator->errors(),
                'error' => true,
            ]);
        }

        $today = Carbon::now();

        $year = Financial_year::where('is_active', '=', true)->first();


         $declaration = Declaration_type::with([
             'sections' => function ($query) {
            $query->orderBy('declaration_sections.section_flow', 'ASC')
                    ->where('status_id',1)
                    ->with(['declarationSections']);
        }
        ])
            ->where('secure_token', '=', $request->declaration_type_secure_token)
            ->first();

        if($declaration == null){

            $response = [
                'statusCode' => 201,
                'message' => 'Samahani Ndugu Kiongozi, Hauna tamko la kuthibitisha.',
                ];

            return response()->json($response, 200);
        }


        foreach ($declaration->sections as $section) {

            $has_data_on_section = DB::table(strtolower($section->table_name))
                        ->where('member_id','=',$request->member_id)
                        ->where('user_declaration_id','=',$request->user_declaration_id)
                        ->where('is_pl','=',$request->is_pl)
                        ->where('is_deleted','1')
                        ->get();

            // dd($check_user_dec);
            // Check if $check_user_dec has data
            if($has_data_on_section->isEmpty()){
                $has_data = 0;
            }else{
                $has_data = 1;
            }
            $section->has_data = $has_data;
        }

        $user_declaration = User_declaration::where('id', $request->user_declaration_id)->first();

        // if ($check != null) {
        //     if ($check->is_confirmed && $declaration->declaration_code == "TRM") {

        //         $response = ['statusCode' => 400, 'message' => 'Tayari umeshathibitisha kutuma tamko hili, kwahyo uwezi kujaza tena', 'data' => $check];

        //         return response()->json($response);
        //     } elseif ($check->is_confirmed && $declaration->declaration_code != "TRM") {

        //         $initDay = Carbon::parse($check->created_at);

        //         $diffDays = $initDay->diffInDays($today);

        //         if ($diffDays <= 7) {

        //             $response = ['statusCode' => 400, 'message' => 'Uwezi kujaza tamko ili kulingana na mda uliotumia awali kujaza aina hii ya tamko,tafadhali subiri zipite siku 7 ndo uweze kujaza tena', 'data' => $check];

        //             return response()->json($response);
        //         }

        //     }
        // }
        $response = ['statusCode' => 200,'declaration' => $declaration, 'user_declaration'=>$user_declaration];

        return response()->json($response, 200);
    }

    public function Returneddeclaration(){
        $declarations = User_declaration::with(['declaration_type.sections'])
            // ->where('user_id','=', '6681')
            ->where('user_id','=', auth()->user()->id)
            ->where('flag', 'PL')
            ->get();

            if ($declarations->isEmpty()){
                $response = ['statusCode' => 400, 'message' => "Hakuna tamko lililorudishwa kwa ajili ya marekebisho"];
                return response()->json($response, 200);
            }

            foreach($declarations as $declaration){

                foreach ($declaration->declaration_type->sections as $section) {
                    // return  $section->table_name;

                $Declaration_type = Declaration_type::with([
                        'sections' => function ($query) {
                       $query->orderBy('declaration_sections.section_flow', 'ASC')
                               ->where('status_id',1)
                               ->with(['declarationSections']);
                   }
                   ])
                       ->where('id', '=', $declaration->declaration_type_id)
                       ->first();


                  $data = DB::table(strtolower($section->table_name))
                    ->where('user_declaration_id', $declaration->id)
                    ->get()
                    ->map(function ($item) {
                        if ($item->is_pl == 0) {
                            $members = Family_member::join('family_member_types','family_member_types.id','=','family_members.family_member_type_id')
                                        ->where('family_members.status_id','=',1)
                                        ->where('family_members.id','=',$item->member_id)
                                        ->select('family_member_types.member_sw','family_members.*')
                                        ->first();

                                        if($members){
                                        $item->member_type = $members->member_sw;
                                        $item->member_first_name = $members->first_name;
                                        $item->member_middle_name = $members->middle_name;
                                        $item->member_last_name = $members->last_name;

                                        }else{
                                        $item->member_type = null;
                                        }
                        }else{
                            $item->member_type = "pl";
                        }
                        return $item;
                    });

                    $requirements = DB::table('requirements')
                        ->join('section_requirements','requirements.id','=','section_requirements.requirement_id')
                        ->join('sections','section_requirements.section_id','=','sections.id')
                        ->where('sections.table_name','=',$section->table_name)
                        ->select('requirements.id','requirements.label','requirements.field_name','requirements.field_type','requirements.end_point')
                        ->get();

                    $section->section_data= $data;
                    $section->requirements = $requirements;

                }

            }

            $response = ['statusCode' => 200, 'message' => 'Matamko/Tamko yaliyorudishwa' , 'declarations' => $declarations];
            return response()->json($response, 200);


    }


    public function DeclarationCreate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'declaration_type' => 'required|integer',
            'flag' => 'required|string',
            'financial_year_id' => 'required|integer',
            'declaration_secure_token' => 'required|string',

        ]);


        if ($validator->fails()) {
            return response()->json([
            'statusCode' => 402,
            'message' => 'validation error',
            'fields' => $validator->errors(),
            'error' => true,
        ]);
        }

        $today = Carbon::now();

        try {
            $declaration = Declaration_type::find($request->input('declaration_type'));

            if ($declaration == null) {
                $response = [
                    'statusCode' => 404,
                    'message' => 'Aina ya Tamko halipo.'
                ];
            return response()->json($response);
            }

            $year = Financial_year::where('is_active', '=', true)->first();

            // $check = User_declaration::where('user_id', '=', auth()->user()->id)
            //     ->where('financial_year_id', '=', $request->input('financial_year_id'))
            //     ->where('declaration_type_id', '=', $declaration->id)
            //     ->where('is_deleted', '=', false)
            //     ->where('flag', '=', 'save')
            //     ->first();
            $check = User_declaration::where('user_id', '=', auth()->user()->id)
                    ->where('financial_year_id', '=', $request->input('financial_year_id'))
                    // ->where('declaration_type_id', '=', $declaration->id)
                    ->where('is_deleted', '=', false)
                    ->where('flag', '=', 'save')
                    ->orderBy('id', 'desc') // Sorting in descending order using the 'id' field
                    ->first();

            if ($check != null) {
                $response = [
                    'statusCode' => 405,
                    // 'message' => 'Karibu, Tafadhali endelea kujaza tamko.',
                    'message' => 'Ndugu kiongozi bado unaendelea kujaza '.$check->declaration_type->type.' , Tafadhali malizia kujaza, Ahsante!.',
                    'user_declaration_id' => $check->id,
                    'declaration_secure_token' =>$request->declaration_secure_token
                ];
            return response()->json($response);
            }

            $user_declaration = User_declaration::create([
                'secure_token' => Str::uuid(),
                'user_id' => auth()->user()->id,
                'declaration_type_id' => $declaration->id,
                'adf_number' => $this->generateAdfNumber($declaration->declaration_code, $request->input('financial_year_id')),
                'financial_year_id' => $request->input('financial_year_id'),
                'flag' => $request->input('flag')
            ]);
            $user_declarations_lookup = $this->user_declarations_lookup($user_declaration->id,$declaration->id);

            return response()->json([
                'statusCode' => 200,
                'message' => 'Umefanikiwa kuanza mchakato wa kujaza Tamko, Tafadhali endelea.',
                'user_declaration_id' => $user_declaration->id,
                'declaration_secure_token' =>$request->declaration_secure_token
            ]);

        } catch (Exception $error) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'Something went wrong.',
                'error' => $error,
            ]);
        }


    }

    public function menuLookupCheck(){
        $menu_lookup = Menu_lookup::where('user_id','=',auth()->user()->id)->first();
        // dd($menu_lookup->stage_three);

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


    public function DeclarationCreateNyongezaPunguzo(Request $request)
    {

        $menu_lookup = Menu_lookup::where('user_id','=',auth()->user()->id)->first();
        // dd($menu_lookup->stage_three);

        if($menu_lookup){

            if($menu_lookup->stage_one === false){
                return response()->json([
                    'statusCode' => 500,
                    'message' => 'Ndugu kiongozi tafadhali jaza kwanza taarifa binafsi ili uweze kuendelea.',
                    'error' => false,
                ]);
            }

            if($menu_lookup->stage_two === false){
                $user = User::where('id',auth()->user()->id)->first();
                if ($user) {

                    if($user->marital_status_id === null || $user->marital_status_id === 2 || $user->marital_status_id === 3){

                        return response()->json([
                            'statusCode' => 501,
                            'message' => 'Ndugu kiongozi tafadhali jaza kwanza taarifa za Mwenza ili uweze kuendelea.',
                            'error' => false,
                        ]);
                    }
               }
            }

            if($menu_lookup->stage_three === false){
                return response()->json([
                    'statusCode' => 502,
                    'message' => 'Ndugu kiongozi tafadhali jaza kwanza taarifa za ajira ili uweze kuendelea.',
                    'error' => false,
                ]);
            }


        }else{

            return response()->json([
                'statusCode' => 500,
                'message' => 'Ndugu kiongozi tafadhali jaza kwanza taarifa binafsi ili uweze kuendelea.',
                'error' => false,
            ]);
        }


        $validator = Validator::make($request->all(), [
            'declaration_type' => 'required|integer',
            'flag' => 'required|string',
            'financial_year_id' => 'required|integer',
        ]);


        if ($validator->fails()) {
            return response()->json([
            'statusCode' => 402,
            'message' => 'validation error',
            'fields' => $validator->errors(),
            'error' => true,
        ]);
        }

        $today = Carbon::now();

        try {
            $declaration = Declaration_type::find($request->input('declaration_type'));

            if ($declaration == null) {
                $response = [
                    'statusCode' => 404,
                    'message' => 'Aina ya Tamko halipo.'
                ];
            return response()->json($response);
            }else{

                $declaration_type_token = $declaration->secure_token;
                $declaration_model = $declaration->declaration_model;
            }

            $year = Financial_year::where('is_active', '=', true)->first();

            $check = User_declaration::where('user_id', '=', auth()->user()->id)
                ->where('financial_year_id', '=', $request->input('financial_year_id'))
                // ->where('declaration_type_id', '=', $declaration->id)
                ->where('flag', '=', 'save')
                ->where('is_deleted', '=', false)
                ->first();
                // dd($check);
            if ($check != null) {
                $response = [
                    'statusCode' => 405,
                    'message' => 'Ndugu kiongozi bado unaendelea kujaza '.$check->declaration_type->type.' , Tafadhali malizia kujaza, Ahsante!.',
                    'user_declaration_id' => $check->id,
                    'declaration_secure_token' =>$request->declaration_secure_token,
                    'declaration_type_token' => $declaration_type_token,
                    'declaration_model' => $declaration_model,
                    'declaration_type_id' => $check->declaration_type_id,
                   'is_nyongeza' => $check->is_nyongeza
            ];
            return response()->json($response);
            }



            $financial_year_check = User_declaration::where('user_id', '=', auth()->user()->id)
                ->where('financial_year_id', '=', $request->input('financial_year_id'))
                ->where('declaration_type_id', '=', $declaration->id)
                ->where('flag', '=', 'submit')
                ->where('is_deleted', '=', false)
                ->first();

            if($financial_year_check != null && $declaration->declaration_model == 2){
                $response = [
                    'statusCode' => 406,
                    'message' => 'Ndugu kiongozi, unaruhusiwa kujaza tamko hili mara moja tu kwa mwaka.',
                    'declaration_secure_token' =>$request->declaration_secure_token
                ];
                return response()->json($response);

            }

            $user_declaration = User_declaration::create([
                'secure_token' => Str::uuid(),
                'user_id' => auth()->user()->id,
                'declaration_type_id' => $declaration->id,
                'adf_number' => $this->generateAdfNumber($declaration->declaration_code, $request->input('financial_year_id')),
                'financial_year_id' => $request->input('financial_year_id'),
                'flag' => $request->input('flag')
            ]);
            $user_declarations_lookup = $this->user_declarations_lookup($user_declaration->id,$declaration->id);
            $generate_section_for_nyongeza_punguzo = $this->generate_section_for_nyongeza_punguzo($user_declaration->id,$declaration->id);

            return response()->json([
                'statusCode' => 200,
                'message' => 'Ndugu kiongozi endelea kuongeza au kupunguza '.$declaration->type.' , Ahsante!.',
                'user_declaration_id' => $user_declaration->id,
                'declaration_secure_token' => $request->declaration_secure_token,
                'is_nyongeza' => User_declaration::where('id',$user_declaration->id)->first()->is_nyongeza,
            ]);

        } catch (Exception $error) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'Tatizo la kimtandao.',
                'error' => $error,
            ]);
        }


    }

    public function generate_section_for_nyongeza_punguzo($user_declaration_id,$declaration_type_id){

        // $user_declaration = User_declaration::where('declaration_type_id', '=', $declaration_type_id)->get();


        $user_declaration = User_declaration::whereNotIn('id', [$user_declaration_id])
                            ->where('user_id',auth()->user()->id)
                            ->where('flag','submit')
                            ->where('is_deleted',false)
                            ->get()
                            ->sortBy('id')
                            ->last();

        if($user_declaration)
        {
            $sections = Declaration_type::join('declaration_sections','declaration_sections.declaration_type_id','=','declaration_types.id')
                                            ->join('sections','sections.id','=','declaration_sections.section_id')
                                            ->where('declaration_types.id',$declaration_type_id)
                                            ->get();
                                            // dd($sections);

            foreach ($sections as $section) {


                if($section->require_nyongeza == true){

                $table_name = strtolower($section->table_name);

                $section_datas = DB::table($table_name)
                    ->where('user_declaration_id', $user_declaration->id)
                    ->where('is_deleted','1')
                    ->get();

                    foreach ($section_datas as $data) {
                        $data = (array) $data;

                        unset($data['id']); // Replace 'id' with your actual primary key column name

                        unset($data['user_declaration_id']); // Replace 'user_declaration_id' with the actual column name

                        $data['user_declaration_id'] = $user_declaration_id;

                        DB::table($table_name)->insert($data);
                    }

                    $update_is_nyongeza = User_declaration::where('id', '=', $user_declaration_id)->update(['is_nyongeza' => true]);
                }

            }


        }

    }


    public function user_declarations_lookup($user_declaration_id,$declaration_type_id){
        $members = Family_member::with(['member_type'])
            ->where('user_id','=',auth()->user()->id)
            ->where('status_id','=',1)
            ->get();

        $Declaration_section = Declaration_section::where('declaration_type_id',$declaration_type_id)->get();


        $pl_lookup_create = UserDeclarationsLookup::create([
            'pl_id' => auth()->user()->id,
            'family_member_id' => auth()->user()->id,
            'status_id' => '0',
            'user_declaration_id' => $user_declaration_id,
            'declaration_section_count' => $Declaration_section->count(),
            'is_pl' => '1',

        ]);

        if($members){
            foreach ($members as $member){
                $member_lookup = UserDeclarationsLookup::create([
                    'pl_id' => auth()->user()->id,
                    'family_member_id' => $member->id,
                    'status_id' => '0',
                    'user_declaration_id' => $user_declaration_id,
                    'declaration_section_count' => $Declaration_section->count(),
                    'is_pl' => '0',

                ]);
            }

        }
    }

    public function familyMemberDeclaration($user_declaration_id){


        try {

            $members = Family_member::with(['member_type'])
                        ->leftjoin('user_declarations_lookup','user_declarations_lookup.family_member_id','=','family_members.id')
                        ->where('family_members.user_id','=',auth()->user()->id)
                        ->where('family_members.status_id','=',1)
                        ->where('user_declarations_lookup.user_declaration_id','=',$user_declaration_id)
                        ->select([
                            'family_members.*',
                            DB::raw('0 as is_pl'),
                            ])
                        ->get();
            $public_leader = User::where('users.id','=',auth()->user()->id)
                        ->leftjoin('user_declarations_lookup','user_declarations_lookup.pl_id','=','users.id')
                        ->where('user_declarations_lookup.user_declaration_id','=',$user_declaration_id)
                        ->select([
                                'users.id',
                                'users.first_name',
                                'users.middle_name',
                                'users.last_name',
                                'users.nationality',
                                DB::raw('1 as is_pl'),

                                ])
                            ->first();
            $declaration_section_count = sectioncountAll($user_declaration_id);

            if ($public_leader) {
                // Call your function and get additional data
                $additionalDataLeader = sectioncount($user_declaration_id, auth()->user()->id, '1');

                // Add the additional data to the result
                $public_leader->declaration_section_completed = $additionalDataLeader;
                $public_leader->declaration_section_count = $declaration_section_count;
            }

            if ($members) {

                foreach ($members as $member) {
                    // Call your function and get additional data for each member
                    $additionalData = sectioncount($user_declaration_id, $member->id, '0');

                    // Add the additional data to the member object
                    $member->declaration_section_completed = $additionalData;
                    $member->declaration_section_count = $declaration_section_count;
            }

                $response = [
                    'statusCode' => 200,
                    'message' => 'Taarifa za Tamko la kiongozi na wanafamilia.',
                    'public_leader' => $public_leader,
                    'family_members' => $members
                ];
            return response()->json($response);
            }else{


                $response = [
                    'statusCode' => 200,
                    'message' => 'Taarifa za Tamko la kiongozi na wanafamilia.',
                    'public_leader' => $public_leader,
                    'family_members' => null
                ];
            return response()->json($response);

            }



        } catch (Exception $error) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'Tatizo la Kimtandao.',
                'error' => $error,
            ]);
        }

    }

    public function declarationSectionsRequirements(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'section_secure_token' =>  ['required','string'],
            'member_id' =>  ['required','integer'],
            'is_pl' =>  ['required','integer'],
            'user_declaration_id' =>  ['required','string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'validation error',
                'fields' => $validator->errors(),
                'error' => true,
            ]);
        }

        // try{


            $section = Section::with([
                'requirements' => function($query)
                {
                $query->with([
                    'requirement' => function($qry){
                        $qry->select('id','label','field_name','field_type','end_point');
                    }
                ])->orderby('requirement_flow','asc')->select('id','secure_token','section_id','requirement_id','requirement_flow');
                }
            ])->join('declaration_sections', 'declaration_sections.id', '=', 'sections.id')
                // ->where('sections.secure_token','=',$request->section_secure_token)
                ->where('sections.secure_token','=',$request->section_secure_token)
                ->first();

                $section = Section::where('sections.secure_token','=',$request->section_secure_token)->first();
                $requirements = Section_requirement::with('requirement')->orderby('requirement_flow','asc')->where('section_id',$section->id)->get();

                $requirements_data = [];
                foreach($requirements as $requirement){
                    $requirement_fields = $requirement->requirement;
                    if($requirement_fields->field_type=="select"){
                        // $url="http://41.59.227.219:9003/".$requirement_fields->end_point;
                        // $response = Http::get($url)->json();

                    $requirements_data[]=$requirement;
                 }
                }
                // dd($requirements_data);

                if($requirements){
                    $section->requirements= $requirements;
                }else{
                $section->requirements= null;
                }

                $data = DB::table(strtolower($section->table_name))
                            ->where('member_id','=',$request->member_id)
                            ->where('user_declaration_id','=',$request->user_declaration_id)
                            ->where('is_pl','=',$request->is_pl)
                            ->where('is_deleted','1')
                            ->get()
                            ->map(function ($item) use ($section) {
                                $item->table_name = $section->table_name;
                                return $item;
                            });
                            // dd(strtolower($request->member_id));
                if($data){
                    $section->section_data= $data;
                }else{
                $section->section_data= null;
                }

                $dataCount = count( $section ->section_data);

                //         $section = Section::where('secure_token',$secure_token)->first();
            //         $section_data = Section_requirement::where()->get();
            // dd($section);

            $response = ['statusCode' => 200, 'section' => $section,'dataCount'=>$dataCount];

            return response()->json($response, 200);


        // } catch (Exception $error) {
        //     return response()->json([
        //         'statusCode' => 402,
        //         'message' => 'Tatizo la Kimtandao.',
        //         'error' => $error,
        //     ]);
        // }
    }

    public function sectionRequirementsForm($secure_token)
    {

        $section = Section::with([
            'requirements' => function($query)
            {
               $query->with([
                   'requirement' => function($qry){
                      $qry->select('id','label','field_name','field_type','end_point');
                   }
               ])->orderby('requirement_flow','asc')->select('id','secure_token','section_id','requirement_id','requirement_flow');
            }
        ])->join('declaration_sections', 'declaration_sections.id', '=', 'sections.id')
            ->where('sections.secure_token','=',$secure_token)
            ->first();
            $data = DB::table(strtolower($section->table_name))
            ->get();

            $section->section_data= $data;
            $dataCount = count( $section ->section_data);

            //         $section = Section::where('secure_token',$secure_token)->first();
           //         $section_data = Section_requirement::where()->get();
          // dd($section);

        $response = ['statusCode' => 200, 'section' => $section,'dataCount'=>$dataCount];

        return response()->json($response, 200);
    }

    public function deleteDeclaration(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'user_declaration_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'validation error',
                'fields' => $validator->errors(),
                'error' => true,
            ]);
        }

        try{

            $data = User_declaration::where('user_id', '=', auth()->user()->id)
                    ->where('id', '=', $request->user_declaration_id)->first();

            $data->is_deleted = true;
            $data->save();

            $declaration = Declaration_type::where('id',$data->declaration_type_id)->first();

            $response = ['statusCode' => 200, 'message' => 'Umefanikiwa kufuta '.$declaration->type.' kikamilifu'];

            return response()->json($response, 200);
        } catch (Exception $error) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'Something went wrong.',
                'error' => $error,
            ]);
        }
    }

    public function declarationSave(Request $request)
    {
        Log::debug($request);

        $validator = Validator::make($request->all(), [
            'declaration_type' => 'required|integer',
            'sections' => 'required|array',
            'flag' => 'required|string',
            'member_id' => 'required|integer',
            'is_pl' => 'required|integer',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'validation error',
                'fields' => $validator->errors(),
                'error' => true,
            ]);
        }

        // $today = Carbon::now();

        // try {
            $declaration = Declaration_type::find($request->input('declaration_type'));

            $year = Financial_year::where('is_active', '=', true)->first();

            $check = User_declaration::where('id', '=', $request->user_declaration_id)->first();

            $sections = $request->input('sections');

            $check->has_password = false;
            // $check->submitted_date = $today;

            $check->save();

        return $this->insertSections($sections, $check, $request);

        // } catch (Exception $error) {
        //     return response()->json([
        //         'statusCode' => 402,
        //         'message' => 'Something went wrong.',
        //         'error' => $error,
        //     ]);
        // }


    }

    public function updateSectionData(Request $request)
    {
        // Log::debug($request);

        $validator = Validator::make($request->all(), [
            'table' => 'required|string',
            'key' => 'required|string',
            'value' => 'required|string',
            'id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'validation error',
                'fields' => $validator->errors(),
                'error' => true,
            ]);
        }
        try {

        $table = strtolower($request->table);
        $key = $request->key;

        if (!Schema::hasColumn($table, $key)) {
            Schema::table($table, function ($table) use ($key) {
                $table->string($key)->nullable();
            });
        }

        DB::table($table)->where('id','=',$request->id)->update([
            $request->key => $request->value
        ]);


        $update = DB::table($table)->where('id','=',$request->id)->first();


        $response = ['statusCode' => 200,
                    'message' => 'Ndugu kiongozi, Umefanikiwa kurekebisha taarifa zako',
                    'table' => $table,
                    'data' => $update
                   ];

        return response()->json($response);

        } catch (Exception $error) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'Something went wrong.',
                'error' => $error,
            ]);
        }


    }

    public function updateReturnedSectionData(Request $request)
    {
        // Log::debug($request);

        $validator = Validator::make($request->all(), [
            'table' => 'required|string',
            'key' => 'required|string',
            'value' => 'required|string',
            'id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'validation error',
                'fields' => $validator->errors(),
                'error' => true,
            ]);
        }
        try {

        $table = strtolower($request->table);
        $key = $request->key;

        if (!Schema::hasColumn($table, $key)) {
            Schema::table($table, function ($table) use ($key) {
                $table->string($key)->nullable();
            });
        }

        DB::table($table)->where('id','=',$request->id)->update([
            $request->key => $request->value,
            'pl_status' => '2'
        ]);


        $update = DB::table($table)->where('id','=',$request->id)->first();


        $response = ['statusCode' => 200,
                    'message' => 'Ndugu kiongozi, Umefanikiwa kurekebisha taarifa zako',
                    'table' => $table,
                    'data' => $update
                   ];

        return response()->json($response);

        } catch (Exception $error) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'Something went wrong.',
                'error' => $error,
            ]);
        }


    }



    public function returnedDeclarationSubmission(Request $request): JsonResponse
    {
        Log::debug($request);

	 $validator = Validator::make($request->all(), [
            'user_declaration_id' => 'required|integer',
            'flag' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'validation error',
                'fields' => $validator->errors(),
                'error' => true,
            ]);
        }

        $today = Carbon::now();

        $year = Financial_year::where('is_active', '=', true)->first();

        $data = User_declaration::where('id', '=', $request->user_declaration_id)->first();

        if ($data) {

            $data->flag = $request->input('flag');

            $data->save();

            $response = [
                'statusCode' => 200,
                'message' => 'Umefanikiwa kutuma Tamko Sekretarieti ya maadili, Ahsante.',
                'data' => $data
            ];
        } else {
            $response = [
                'statusCode' => 404,
                'message' => 'User declaration haipatikani.'
            ];
        }

        return response()->json($response);
    }

    public function updateSection(Request $request,$id)
    {

     //       return $request->getContent();

        $table = strtolower($request['section']['table']);
        $data = $request['section']['data'];

        if (count($data) > 0) {

            foreach ($data as $values) {

                foreach ($values as $key => $value) {

                    DB::table($table)->where('id','=',$id)->update([
                        $key => $value
                    ]);
                }

                $data = DB::table($table)->orderBy('id','DESC')->first();

                $response = ['statusCode' => 200, 'message' => 'Umefanikiwa kurekebisha taarifa zako', 'table' => $table,'data' => $data];

                return response()->json($response);
            }

        }

    }

    public function declarationSubmission(Request $request): JsonResponse
    {
        // Log::debug($request);

	 $validator = Validator::make($request->all(), [
            'user_declaration_id' => 'required|integer',
            'flag' => 'required|string',
            'is_late'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'validation error',
                'fields' => $validator->errors(),
                'error' => true,
            ]);
        }

        // $declaration = Declaration_type::find($request->input('declaration_type'));
        $today = Carbon::now();

        $year = Financial_year::where('is_active', '=', true)->first();

        $data = User_declaration::where('id', '=', $request->user_declaration_id)->first();
        // dd($data);

        if ($data) {
            // $data->update([
            //     'flag' => $request->input('flag')
            // ]);

            $data->flag = $request->input('flag');
            $data->is_late = $request->input('is_late');
            $data->submitted_date = $today;


            if($request->input('is_late') == true){
            $data->late_reason = $request->input('late_reason');
            }

            if($request->input('late_reason_attachment')){
                $add_attachment = $this->base64_to_file($request->late_reason_attachment, 'latereasons');
                $data->late_reason_attachment = $add_attachment;

            }

            $data->save();

            $response = [
                'statusCode' => 200,
                'message' => 'Umefanikiwa kutuma Tamko Sekretarieti ya maadili, Ahsante.',
                'data' => $data
            ];
        } else {
            $response = [
                'statusCode' => 404,
                'message' => 'User declaration haipatikani.'
            ];
        }

        return response()->json($response);
    }

    public function base64_to_file($base64_attachment,$folder){


        $base64Image = $base64_attachment;

        $base64Image = preg_replace('#^data:image/\w+;base64,#i', '', $base64Image);

        $imageData = base64_decode($base64Image);

        $filename = uniqid() . '.pdf';

        $folderPath = public_path('attahments/'.$folder);

        if (!File::isDirectory($folderPath)) {
            File::makeDirectory($folderPath, 0755, true, true);
        }

        $filePath = $folderPath . '/' . $filename;
        file_put_contents($filePath, $imageData);


        return 'attahments/'.$folder.'/'.$filename;


    }

    public function previewAdf(Request $request)
    {
        $year = Financial_year::where('is_active', '=', 1)->first();
          $declaration = User_declaration::with([
            'declaration_type' => function ($query) {
                $query->with([
                    'sections' => function ($qry) {
                        // $qry->select('*');
                        $qry->orderBy('section_flow', 'asc');
                        $qry->where('sections.status_id','1');
                    }
                ]);
            },
            'user' => function ($query) {

                $query->leftjoin('marital_statuses','marital_statuses.id','=','users.marital_status_id');
                $query->leftjoin('hadhi','hadhi.id','=','users.hadhi_id');
                $query->leftjoin('sexes','sexes.id','=','users.sex_id');

                $query->leftjoin('countries AS current_country', function ($join) {
                    $join->on('current_country.id', '=', DB::raw('CAST(users.country_current AS bigint)'));
                });
                $query->leftjoin('wards', function ($join) {
                    $join->on('wards.id', '=', DB::raw('CAST(users.ward_current AS bigint)'));
                });
                $query->leftjoin('districts', function ($join) {
                    $join->on('districts.id', '=', DB::raw('CAST(users.district_current AS bigint)'));
                });
                $query->leftjoin('regions', function ($join) {
                    $join->on('regions.id', '=', DB::raw('CAST(users.region_current AS bigint)'));
                });
                $query->leftjoin('villages', function ($join) {
                    $join->on('villages.id', '=', DB::raw('CAST(users.village_id AS bigint)'));
                });
                $query->select('users.*','marital_statuses.marital_sw as marital_name','hadhi.hadhi_name','sexes.sex as sex_name',
                               'wards.ward_name as ward_current_name','districts.district_name as district_current_name','regions.region_name as region_current_name',
                               'current_country.country AS country_current_name','villages.name as village_current_name');
            },
        ])
            ->where('id','=', $request->user_declaration_id)
            ->where('is_deleted', '=', false)
            ->first();
        if ($declaration == null){
            $response = ['statusCode' => 400, 'message' => "Auna data yeyote ambayo umejaza kwenye tamko hili,tafadhali jaza kwanza taarifa ili uweze kupata taarifa husika la tamko lako"];
            return response()->json($response, 200);
        }



        $declaration->pl_empty_sections = $this->pl_empty_sections($declaration->declaration_type->sections,$request->user_declaration_id,auth()->user()->id);
        $declaration->member_empty_sections = $this->member_empty_sections($declaration->declaration_type->sections,$request->user_declaration_id,auth()->user()->id);
        // dd($member_empty_sections);

        foreach ($declaration->declaration_type->sections as $section) {
            // return  $section->table_name;
            $data = DB::table(strtolower($section->table_name))
            ->where('user_declaration_id', $declaration->id)
            ->where('is_deleted', '1')
            ->get()
            ->map(function ($item) {
                if ($item->is_pl == 0) {
                    $members = Family_member::join('family_member_types','family_member_types.id','=','family_members.family_member_type_id')
                             ->where('family_members.status_id','=',1)
                             ->where('family_members.id','=',$item->member_id)
                             ->select('family_member_types.member_sw','family_members.*')
                             ->first();

                             if($members){
                                $item->member_type = $members->member_sw;
                                $item->member_first_name = $members->first_name;
                                $item->member_middle_name = $members->middle_name;
                                $item->member_last_name = $members->last_name;

                             }else{
                                $item->member_type = null;
                             }
                }else{
                    $item->member_type = "pl";
                }
                return $item;
            });

           $requirements = DB::table('requirements')
                ->join('section_requirements','requirements.id','=','section_requirements.requirement_id')
                ->join('sections','section_requirements.section_id','=','sections.id')
                ->where('sections.table_name','=',$section->table_name)
                ->select('requirements.id','requirements.label','requirements.field_name','requirements.field_type')
                ->orderBy('section_requirements.requirement_flow', 'asc')
                ->get();


            $section->section_data= $data;
            $section->requirements = $requirements;
        }
        $taarifa_za_ajira = Sectiontaarafa478::where('user_id',auth()->user()->id)
                                              ->with('kata_sasa_name')
                                              ->with('wilaya_sasa_name')
                                              ->with('mkoa_sasa_name')
                                              ->with('userDeclaration')
                                              ->with('mwaajiri')
                                              ->with('ainaya_ajira')
                                              ->with('marital_status')
                                              ->with('title_name')
                                              ->with('councils')
                                              ->with('village')
                                              ->with('country')
                                              ->orderBy('id', 'desc')
                                              ->first();

        $password = Declaration_download::where('user_declaration_id',$declaration->id)->orderByDesc('id')->first();

        if($password){

            $password = $password->password;
        }else{
            $password = null;

        }

        $response = ['statusCode' => 200, 'declaration' => $declaration, 'taarifa_za_ajira' => $taarifa_za_ajira, 'year' => $year->year, 'password' => $password];
        return response()->json($response, 200);
    }

    public function previewAdfNoAuth(Request $request)
    {
        $year = Financial_year::where('is_active', '=', 1)->first();
          $declaration = User_declaration::with([
            'declaration_type' => function ($query) {
                $query->with([
                    'sections' => function ($qry) {
                        // $qry->select('*');
                        $qry->orderBy('section_flow', 'asc');
                        $qry->where('sections.status_id','1');
                    }
                ]);
            },
            'user' => function ($query) {

                $query->leftjoin('marital_statuses','marital_statuses.id','=','users.marital_status_id');
                $query->leftjoin('hadhi','hadhi.id','=','users.hadhi_id');
                $query->leftjoin('sexes','sexes.id','=','users.sex_id');

                $query->leftjoin('countries AS current_country', function ($join) {
                    $join->on('current_country.id', '=', DB::raw('CAST(users.country_current AS bigint)'));
                });
                $query->leftjoin('wards', function ($join) {
                    $join->on('wards.id', '=', DB::raw('CAST(users.ward_current AS bigint)'));
                });
                $query->leftjoin('districts', function ($join) {
                    $join->on('districts.id', '=', DB::raw('CAST(users.district_current AS bigint)'));
                });
                $query->leftjoin('regions', function ($join) {
                    $join->on('regions.id', '=', DB::raw('CAST(users.region_current AS bigint)'));
                });
                $query->leftjoin('villages', function ($join) {
                    $join->on('villages.id', '=', DB::raw('CAST(users.village_id AS bigint)'));
                });
                $query->select('users.*','marital_statuses.marital_sw as marital_name','hadhi.hadhi_name','sexes.sex as sex_name',
                               'wards.ward_name as ward_current_name','districts.district_name as district_current_name','regions.region_name as region_current_name',
                               'current_country.country AS country_current_name','villages.name as village_current_name');
            },
        ])
            ->where('id','=', $request->user_declaration_id)
            ->where('is_deleted', '=', false)
            ->first();
        if ($declaration == null){
            $response = ['statusCode' => 400, 'message' => "Auna data yeyote ambayo umejaza kwenye tamko hili,tafadhali jaza kwanza taarifa ili uweze kupata taarifa husika la tamko lako"];
            return response()->json($response, 200);
        }



        $declaration->pl_empty_sections = $this->pl_empty_sections($declaration->declaration_type->sections,$request->user_declaration_id,$request->user_id);
        $declaration->member_empty_sections = $this->member_empty_sections($declaration->declaration_type->sections,$request->user_declaration_id,$request->user_id);
        // dd($member_empty_sections);

        foreach ($declaration->declaration_type->sections as $section) {
            // return  $section->table_name;
            $data = DB::table(strtolower($section->table_name))
            ->where('user_declaration_id', $declaration->id)
            ->where('is_deleted', '1')
            ->get()
            ->map(function ($item) {
                if ($item->is_pl == 0) {
                    $members = Family_member::join('family_member_types','family_member_types.id','=','family_members.family_member_type_id')
                             ->where('family_members.status_id','=',1)
                             ->where('family_members.id','=',$item->member_id)
                             ->select('family_member_types.member_sw','family_members.*')
                             ->first();

                             if($members){
                                $item->member_type = $members->member_sw;
                                $item->member_first_name = $members->first_name;
                                $item->member_middle_name = $members->middle_name;
                                $item->member_last_name = $members->last_name;

                             }else{
                                $item->member_type = null;
                             }
                }else{
                    $item->member_type = "pl";
                }
                return $item;
            });

           $requirements = DB::table('requirements')
                ->join('section_requirements','requirements.id','=','section_requirements.requirement_id')
                ->join('sections','section_requirements.section_id','=','sections.id')
                ->where('sections.table_name','=',$section->table_name)
                ->select('requirements.id','requirements.label','requirements.field_name','requirements.field_type')
                ->orderBy('section_requirements.requirement_flow', 'asc')
                ->get();


            $section->section_data= $data;
            $section->requirements = $requirements;
        }
        $taarifa_za_ajira = Sectiontaarafa478::where('user_id',$request->user_id)
                                              ->with('kata_sasa_name')
                                              ->with('wilaya_sasa_name')
                                              ->with('mkoa_sasa_name')
                                              ->with('userDeclaration')
                                              ->with('mwaajiri')
                                              ->with('ainaya_ajira')
                                              ->with('marital_status')
                                              ->with('title_name')
                                              ->with('councils')
                                              ->with('village')
                                              ->with('country')
                                              ->orderBy('id', 'desc')
                                              ->first();

        $password = Declaration_download::where('user_declaration_id',$declaration->id)->orderByDesc('id')->first();

        if($password){

            $password = $password->password;
        }else{
            $password = null;

        }

        $response = ['statusCode' => 200, 'declaration' => $declaration, 'taarifa_za_ajira' => $taarifa_za_ajira, 'year' => $year->year, 'password' => $password];
        return response()->json($response, 200);
    }

    public function pl_empty_sections($sections,$declaration_id,$user_id){


            $pl_empty_sections = [];
                $empty_sections = [];
                foreach($sections as $section){
                    $empty_sections_check = DB::table(strtolower($section->table_name))
                            ->where('user_declaration_id', $declaration_id)
                            ->where('member_id', $user_id)
                            ->where('is_deleted','1')
                            ->first();

                    if ($empty_sections_check == null) {
                        $empty_sections[] = $section->section_name;
                    }
                    // dd(auth()->user()->id);
                }
                $user_data['section_name'] = $empty_sections;
                $pl_empty_sections[] = $user_data;

        return $pl_empty_sections;


    }

    public function member_empty_sections($sections,$declaration_id,$user_id){
        $family_members = Family_member::with(['member_type'])
            ->where('user_id','=',$user_id)
            ->where('status_id','=',1)
            ->get();
            // dd($family_members);
        if ($family_members->isNotEmpty()) {
            $family_member_empty_sections = [];
            foreach($family_members as $family_member){
                $empty_sections = [];
                foreach($sections as $section){
                    $empty_sections_check = DB::table(strtolower($section->table_name))
                            ->where('user_declaration_id', $declaration_id)
                            ->where('member_id', $family_member->id)
                            ->where('is_deleted','1')
                            ->first();

                    if ($empty_sections_check == null) {
                        $empty_sections[] = $section->section_name;
                    }
                }
                $user_data['id'] = $family_member->id;
                $user_data['member_type'] = $family_member->member_type->member_sw;
                $user_data['full_name'] = $family_member->first_name.' '.$family_member->middle_name.' '.$family_member->last_name;
                $user_data['section_name'] = $empty_sections;
                $family_member_empty_sections[] = $user_data;
            }
        }else{
            $family_member_empty_sections = [];
        }

        return $family_member_empty_sections;


    }

    public function confirmDeclarationPreview(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'user_declaration_id' =>  ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'validation error',
                'fields' => $validator->errors(),
                'error' => true,
            ]);
        }



        $user_declaration_id = $request->input('user_declaration_id');

        $update = User_declaration::where('id','=',$user_declaration_id)->first();


        if ($update == null){

            $response = ['statusCode' => 400, 'message' => 'Tamko namba'.$user_declaration_id.' haipo, Ahsante'];
            return response()->json($response, 200);
        }

        $user_declaration = $update->id;

        if($update->has_password == false){

        $update->has_password = true;

        $password = $this->generateRandomString(10);

            $download = Declaration_download::create([
                'secure_token' => Str::uuid(),
                'downloader_secure_token' => auth()->user()->secure_token,
                'user_declaration_id' => $user_declaration,
                'password' => $password
            ]);

        }

        $update->is_confirmed = true;
        $update->save();

        $password = Declaration_download::where('user_declaration_id',$user_declaration)->orderByDesc('id')->first()->password;

        $response = ['statusCode' => 200,
        'password' => $password,
        'message' => 'Umefanikiwa kuthibitisha, sasa unaweza kuwasilisha tamko hili Sekretarieti ya maadili, Ahsante, Tafadhali tumia nywila hii kupakua tamko',
        'data' => $update];
        return response()->json($response, 200);
    }

    public function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public function downloadAdf(Request $request): JsonResponse
    {
        $year = Financial_year::where('is_active', '=', 1)->first();
          $declaration = User_declaration::with([
            'declaration_type' => function ($query) {
                $query->with([
                    'sections' => function ($qry) {
                        // $qry->select('section_name', 'table_name');
                        $qry->orderBy('section_flow', 'asc');
                        $qry->where('sections.status_id','1');
                    }
                ]);
            },
            'user' => function ($query) {

                $query->leftjoin('marital_statuses','marital_statuses.id','=','users.marital_status_id');
                $query->leftjoin('hadhi','hadhi.id','=','users.hadhi_id');
                $query->leftjoin('sexes','sexes.id','=','users.sex_id');
                // $query->leftjoin('countries AS birth_country', function ($join) {
                //     $join->on('birth_country.id', '=', DB::raw('CAST(users.country_birth AS bigint)'));
                // });

                $query->leftjoin('countries AS current_country', function ($join) {
                    $join->on('current_country.id', '=', DB::raw('CAST(users.country_current AS bigint)'));
                });
                $query->leftjoin('wards', function ($join) {
                    $join->on('wards.id', '=', DB::raw('CAST(users.ward_current AS bigint)'));
                });
                $query->leftjoin('districts', function ($join) {
                    $join->on('districts.id', '=', DB::raw('CAST(users.district_current AS bigint)'));
                });
                $query->leftjoin('regions', function ($join) {
                    $join->on('regions.id', '=', DB::raw('CAST(users.region_current AS bigint)'));
                });
                $query->select('users.*','marital_statuses.marital_sw as marital_name','hadhi.hadhi_name','sexes.sex as sex_name',
                               'wards.ward_name as ward_current_name','districts.district_name as district_current_name','regions.region_name as region_current_name',
                               'current_country.country AS country_current_name',);
            },
        ])
            ->where('id','=', $request->user_declaration_id)
            ->where('user_id', '=', auth()->user()->id)
            ->where('financial_year_id', '=', $year->id)
            ->first();
            // dd($declaration);
        if ($declaration == null){
            $response = ['statusCode' => 400, 'message' => "Auna data yeyote ambayo umejaza kwenye tamko hili,tafadhali jaza kwanza taarifa ili uweze kupata taarifa husika la tamko lako"];
            return response()->json($response, 200);
        }

        foreach ($declaration->declaration_type->sections as $section) {
            // return  $section->table_name;
            $data = DB::table(strtolower($section->table_name))
            ->where('user_declaration_id', $declaration->id)
            ->get()
            ->map(function ($item) {
                if ($item->is_pl == 0) {
                    $members = Family_member::join('family_member_types','family_member_types.id','=','family_members.family_member_type_id')
                             ->where('family_members.status_id','=',1)
                             ->where('family_members.id','=',$item->member_id)
                             ->select('family_member_types.member_sw','family_members.*')
                             ->first();

                             if($members){
                                $item->member_type = $members->member_sw;
                                $item->member_first_name = $members->first_name;
                                $item->member_middle_name = $members->middle_name;
                                $item->member_last_name = $members->last_name;

                             }else{
                                $item->member_type = null;
                             }
                }else{
                    $item->member_type = "pl";
                }
                return $item;
            });

           $requirements = DB::table('requirements')
                ->join('section_requirements','requirements.id','=','section_requirements.requirement_id')
                ->join('sections','section_requirements.section_id','=','sections.id')
                ->where('sections.table_name','=',$section->table_name)
                ->select('requirements.id','requirements.label','requirements.field_name','requirements.field_type')
                ->orderBy('section_requirements.requirement_flow', 'asc')
                ->get();

            $section->section_data= $data;
            $section->requirements = $requirements;
        }
        $taarifa_za_ajira = Sectiontaarafa478::where('user_id',auth()->user()->id)
                                              ->with('kata_sasa_name')
                                              ->with('wilaya_sasa_name')
                                              ->with('mkoa_sasa_name')
                                              ->with('userDeclaration')
                                              ->with('mwaajiri')
                                              ->with('ainaya_ajira')
                                              ->with('marital_status')
                                              ->with('title_name')
                                              ->first();

        $password = Declaration_download::where('user_declaration_id',$request->user_declaration_id)->orderByDesc('id')->first();
        $password->download_date = Carbon::now();
        $password->save();
        User_declaration::where('id','=', $request->user_declaration_id)->update(['is_download' => true]);

        $response = ['statusCode' => 200, 'password' => $password->password , 'declaration' => $declaration, 'taarifa_za_ajira' => $taarifa_za_ajira, 'year' => $year->year];
        return response()->json($response, 200);
    }



    public function downloadAdfAuth(Request $request): JsonResponse
    {
        $year = Financial_year::where('is_active', '=', 1)->first();
          $declaration = User_declaration::with([
            'declaration_type' => function ($query) {
                $query->with([
                    'sections' => function ($qry) {
                        $qry->select('section_name', 'table_name');
                    }
                ]);
            },
            'user' => function ($query) {

                $query->leftjoin('marital_statuses','marital_statuses.id','=','users.marital_status_id');
                $query->leftjoin('hadhi','hadhi.id','=','users.hadhi_id');
                $query->leftjoin('sexes','sexes.id','=','users.sex_id');
                $query->leftjoin('countries AS birth_country', function ($join) {
                    $join->on('birth_country.id', '=', DB::raw('CAST(users.country_birth AS bigint)'));
                });

                $query->leftjoin('countries AS current_country', function ($join) {
                    $join->on('current_country.id', '=', DB::raw('CAST(users.country_current AS bigint)'));
                });
                $query->leftjoin('wards', function ($join) {
                    $join->on('wards.id', '=', DB::raw('CAST(users.ward_current AS bigint)'));
                });
                $query->leftjoin('districts', function ($join) {
                    $join->on('districts.id', '=', DB::raw('CAST(users.district_current AS bigint)'));
                });
                $query->leftjoin('regions', function ($join) {
                    $join->on('regions.id', '=', DB::raw('CAST(users.region_current AS bigint)'));
                });
                $query->select('users.*','marital_statuses.marital_sw as marital_name','hadhi.hadhi_name','sexes.sex as sex_name',
                               'wards.ward_name as ward_current_name','districts.district_name as district_current_name','regions.region_name as region_current_name',
                               'birth_country.country AS country_birth_name','current_country.country AS country_current_name',);
            },
        ])
            ->where('id','=', $request->user_declaration_id)
            ->where('financial_year_id', '=', $year->id)
            ->first();
        if ($declaration == null){
            $response = ['statusCode' => 400, 'message' => "Auna data yeyote ambayo umejaza kwenye tamko hili,tafadhali jaza kwanza taarifa ili uweze kupata taarifa husika la tamko lako"];
            return response()->json($response, 200);
        }

        foreach ($declaration->declaration_type->sections as $section) {
            // return  $section->table_name;
            $data = DB::table(strtolower($section->table_name))
            ->where('user_declaration_id', $declaration->id)
            ->get()
            ->map(function ($item) {
                if ($item->is_pl == 0) {
                    $members = Family_member::join('family_member_types','family_member_types.id','=','family_members.family_member_type_id')
                             ->where('family_members.status_id','=',1)
                             ->where('family_members.id','=',$item->member_id)
                             ->select('family_member_types.member_sw','family_members.*')
                             ->first();

                             if($members){
                                $item->member_type = $members->member_sw;
                                $item->member_first_name = $members->first_name;
                                $item->member_middle_name = $members->middle_name;
                                $item->member_last_name = $members->last_name;

                             }else{
                                $item->member_type = null;
                             }
                }else{
                    $item->member_type = "pl";
                }
                return $item;
            });

           $requirements = DB::table('requirements')
                ->join('section_requirements','requirements.id','=','section_requirements.requirement_id')
                ->join('sections','section_requirements.section_id','=','sections.id')
                ->where('sections.table_name','=',$section->table_name)
                ->select('requirements.id','requirements.label','requirements.field_name','requirements.field_type')
                ->get();

            $section->section_data= $data;
            $section->requirements = $requirements;
        }
        $taarifa_za_ajira = Sectiontaarafa478::where('user_id',$declaration->user_id)
                                              ->with('kata_sasa_name')
                                              ->with('wilaya_sasa_name')
                                              ->with('mkoa_sasa_name')
                                              ->with('userDeclaration')
                                              ->with('mwaajiri')
                                              ->with('ainaya_ajira')
                                              ->with('marital_status')
                                              ->with('title_name')
                                              ->first();
        $password = Declaration_download::where('user_declaration_id',$declaration->id)->first();
        $response = ['statusCode' => 200, 'password' => decrypt($password->password) , 'declaration' => $declaration, 'taarifa_za_ajira' => $taarifa_za_ajira, 'year' => $year->year];
        return response()->json($response, 200);
    }

    public function ADFDownloadHistory(){

        $download_histories = Declaration_download::with([
            'user_declaration' => function($query){
             $query->with([
                 'declaration_type' => function($qry){
                   $qry->select('id','type');
                 }
             ])
                 ->select('id','declaration_type_id','adf_number','submitted_date');
            },
        ])
        ->where('downloader_secure_token','=',auth()->user()->secure_token)
            ->get();

        $response = ['statusCode' => 200, 'data' => $download_histories];

        return response()->json($response, 200);
    }

    public function ADFSubmittedList(){

        try {
        $declaration = User_declaration::where('user_id', '=', auth()->user()->id)->where('flag','=','submit')->get();

            if ($declaration) {
                return response()->json([
                    'statusCode' => 400,
                    'message' => 'Orodha ya matamko uliyotuma',
                    'declaration' => $declaration,
                    'error' => false,
                ]);


            } else{

                return response()->json([
                    'statusCode' => 400,
                    'message' => 'Hauna Tamko ulilotuma',
                    'declaration' => null,
                    'error' => false,
                ]);
            }
        } catch (Exception $error) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'Something went wrong.',
                'error' => $error,
            ]);
        }
    }

    public function getDeclarationReceipt(Request $request): JsonResponse
    {

        $active_year = Financial_year::where('is_active', '=', 1)->first();

        $declaration = User_declaration::with([
            'declaration_type',
            'user' => function ($query) {
                $query->select('id', 'file_number', 'first_name', 'middle_name', 'last_name', 'nida', 'phone_number');
            },
        ])
            ->where('id','=', $request->user_declaration_id)
            ->first();

        // $declaration = User_declaration::with([
        //     'declaration_type',
        //     'user' => function ($query) {
        //         $query->select('id', 'file_number', 'first_name', 'middle_name', 'last_name', 'nida', 'phone_number');
        //     },
        // ])
        //     ->where('declaration_type_id','=', $request->declaration_type)
        //     ->where('user_id', '=', auth()->user()->id)
        //     ->where('financial_year_id', '=', $active_year->id)
        //     ->first();

        if ($declaration == null){

            $response = ['statusCode' => 400, 'message' => "Auna data yeyote ambayo umejaza kwenye tamko hili,tafadhali jaza kwanza taarifa ili uweze kupata taarifa husika la tamko lako"];

            return response()->json($response, 200);
        }

        $response = ['statusCode' => 200, 'message' => "Umefanikiwa kutuma tamko lako lenye kumbukumbu namba: ".$declaration->adf_number." Sekretarieti ya maadili.", 'declaration' => $declaration, 'year' => $active_year->year];

        return response()->json($response, 200);
    }

    public function sectionDataDelete(Request $request): JsonResponse
    {
        // Log::debug($request);

        // dd($request);
        $validator = Validator::make($request->all(), [
            'data_id' => 'required|integer',
            'table_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'validation error',
                'fields' => $validator->errors(),
                'error' => true,
            ]);
        }

        // try{

        $data = DB::table(strtolower($request->table_name))->where('id',$request->data_id)->first();
        // $data_delete = DB::table(strtolower($request->table_name))->where('id',$request->data_id)->delete();

        $update_data = [
            'is_deleted' => 0,
            'reason' => $request->reason,
        ];

        DB::table(strtolower($request->table_name))->where('id',$request->data_id)->update($update_data);


        $has_data_on_section = DB::table(strtolower($request->table_name))
                        ->where('member_id','=',$data->member_id)
                        ->where('user_declaration_id','=',$data->user_declaration_id)
                        ->where('is_pl','=',$data->is_pl)
                        ->where('is_deleted','1')
                        ->get();

        if($has_data_on_section->isEmpty()){
            $updateResult = DB::table('user_declarations_lookup')
            ->where('user_declaration_id', $data->user_declaration_id)
            ->where('pl_id', auth()->user()->id)
            ->where('family_member_id', $data->member_id)
            ->decrement('declaration_section_completed'); // decrement the field by 1
        }

        $response = ['statusCode' => 200, 'message' => 'Umefanikiwa kufuta taarifa ya section kikamilifu'];

        return response()->json($response, 200);
        // } catch (Exception $error) {
        //     return response()->json([
        //         'statusCode' => 402,
        //         'message' => 'Something went wrong.',
        //         'error' => $error,
        //     ]);
        // }
    }

    private function generateAdfNumber($declarationCode, $year): string
    {
        return 'ADF' . '-' . $declarationCode . '-' . $year . '-' . mt_rand(100, 999);
    }

    private function generateADFPassword(): string
    {

        return Str::random(10);
    }

    /**
     * @param mixed $sections
     * @param $check
     * @return JsonResponse
     */
    private function insertSections(mixed $sections, $check, $request): JsonResponse
    {

        $array = [];

        foreach ($sections as $section) {


            // $table = strtolower($section['section']['table']);
               $table = strtolower(trim($section['section']['table']));
            if (count($section['section']['data']) > 0) {

                foreach ($section['section']['data'] as $values) {
                    // $table = strtolower($section['section']['table']);
                    $table = strtolower(trim($section['section']['table']));


                    $new_object = new stdClass();
                    $object = new stdClass();
                    $object->user_declaration_id = $request->user_declaration_id;
                    $object->member_id = $request->member_id;
                    $object->is_pl = $request->is_pl;
                    foreach ($values as $key => $value) {
                        $key=strtolower(trim($key));
                        $object->$key = $value;
                        $new_object = $object;


                        if (!Schema::hasColumn($table, $key)) {
                            Schema::table($table, function ($table) use ($key) {
                                $table->string($key)->nullable();
                            });
                        }

                    }


                    $has_data_on_section = DB::table(strtolower($table))
                                            ->where('member_id','=',$request->member_id)
                                            ->where('user_declaration_id','=',$request->user_declaration_id)
                                            ->where('is_pl','=',$request->is_pl)
                                            ->where('is_deleted','1')
                                            ->get();

                    if($has_data_on_section->isEmpty()){
                        $updateResult = DB::table('user_declarations_lookup')
                        ->where('user_declaration_id', $request->user_declaration_id)
                        ->where('pl_id', auth()->user()->id)
                        ->where('family_member_id', $request->member_id)
                        ->increment('declaration_section_completed'); // Increment the field by 1
                    }


                    $array[] = $new_object;

                    $encode = json_encode($array, 1);
                    $row = json_decode($encode, true);

                     DB::table($table)->insert($row);

                     if($request->is_pl == 1){
                        $update_UserDeclarationsLookup = UserDeclarationsLookup::where('user_declaration_id',$request->user_declaration_id)
                                                         ->where('pl_id',auth()->user()->id)
                                                         ->where('family_member_id',auth()->user()->id)
                                                         ->first();
                     }else{
                        $update_UserDeclarationsLookup = UserDeclarationsLookup::where('user_declaration_id',$request->user_declaration_id)
                                                         ->where('pl_id',auth()->user()->id)
                                                         ->where('family_member_id',$request->member_id)
                                                         ->first();
                     }


                    $data = DB::table($table)->orderBy('id','DESC')->first();


                    $response = ['statusCode' => 200,
                    'message' => 'Umefanikiwa kutuma taarifa za tamko kikamilifu',
                    'table' => $table,
                    'data' => $data];

                    //  Log::debug($response);

                    return response()->json($response);
                }

            }
        }

    }


    public function integrityPledge(Request $request){


        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string',
            'date_of_appointment' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'validation error',
                'fields' => $validator->errors(),
                'error' => true,
            ]);
        }
        try {



        $integrity_pledge = integrity_pledge::create([
            'secure_token' => Str::uuid(),
            'user_id' => auth()->user()->id,
            'date_of_appointment' => $request->input('date_of_appointment'),
            'title_id' => auth()->user()->title_id,
            'approval_status' => 'RECEIVED',
            'current_stage' => '3',
        ]);


        $response = ['statusCode' => 200,
                    'message' => 'Ahadi ya Uadilifu imepokelewa kikamilifu!',
                   ];

        return response()->json($response);

        } catch (Exception $error) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'Something went wrong.',
                'error' => $error,
            ]);
        }

    }



}
