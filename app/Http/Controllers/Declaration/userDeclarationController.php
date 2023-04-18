<?php

namespace App\Http\Controllers\Declaration;

use App\Http\Controllers\Controller;
use App\Models\Asset_declaration_window;
use App\Models\Declaration_type;
use App\Models\Financial_year;
use App\Models\User;
use App\Models\User_declaration;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class userDeclarationController extends Controller
{
    public function declarations(): JsonResponse
    {

        $declaration_window = Asset_declaration_window::with([
            'declarations' => function($query){
               $query->select('id','secure_token','type');
            }
        ])
            ->where('is_active','=',true)
            ->select('id','declaration_type_id','is_active')
            ->get();

//        $declarations = Declaration_type::get();

        $response = ['declaration_window' => $declaration_window];

        return response()->json($response,200);

    }

//=> function($query){
//    $query->with([
//        'requirements.requirement'
//    ]);
//}

    public function declarationForm($secure_token): JsonResponse
    {
        $declaration = Declaration_type::with([
            'sections' => function($query){
              $query->with([
                  'requirements' => function($qry){
                    $qry->with([
                        'requirement' => function($qy){
                          $qy->select('id','label','field_name','field_type');
                        }
                    ])->select('id','section_id','requirement_id');
                  }
              ]);
            }
        ])
            ->where('secure_token','=',$secure_token)
            ->first();

        $response = ['declaration' => $declaration];

        return response()->json($response,200);
    }

    public function declarationSubmission(Request $request): JsonResponse|array
    {

        $validator = Validator::make($request->all(), [
            'declaration_type' => 'required|integer',
            'sections' => 'required|array',
            'flag' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        try {
            $declaration = Declaration_type::find($request->input('declaration_type'));

            $year = Financial_year::where('is_active','=',true)->first();

            $check =  User_declaration::where('user_id','=',auth()->user()->id)
                ->where('financial_year_id','=',$year->id)
                ->where('declaration_type_id','=',$declaration->id)
                ->first();

            $sections = $request->input('sections');

            if ($check == null){

                $user_declaration = User_declaration::create([
                    'secure_token' => Str::uuid(),
                    'user_id' => auth()->user()->id,
                    'declaration_type_id' => $declaration->id,
                    'adf_number' => $this->generateAdfNumber($declaration->declaration_code,$year->year),
                    'financial_year_id' => $year->id,
                    'flag' => $request->input('flag')
                ]);

                foreach ($sections as $section){

                    if (count($section->section->data) > 0){
                        DB::table($section->section->table)->updateOrInsert(
                            $section->section->data,
                            ['user_declaration_id' => $user_declaration->id]
                        );
                    }
                }


                $response = ['statusCode' => 200, 'message' => 'Tamko lako limetumwa kikamilifu'];

                return response()->json($response);

            }


            foreach ($sections as $section){

                return $section->table;
                if (count($section->section->data) > 0) {
                    DB::table($section->section->table)->updateOrInsert(
                        $section->section->data,
                        ['user_declaration_id' => $check->id]
                    );
                }
            }

            $response = ['statusCode' => 200, 'message' => 'Tamko lako limetumwa kikamilifu'];

            return response()->json($response);
        }catch (Exception $error) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'Something went wrong.',
                'error' => $error,
            ]);
        }


    }

    public function previewAdf(): JsonResponse
    {

        $year = Financial_year::where('is_active','=',1)->first();

        $declaration = User_declaration::with([
            'declaration_type',
            'user',
            'employments' => function($query){
               $query->with([
                   'title',
                   'office',
                   'employment_type'
               ]);
            },
            'cashes',
            'banks',
            'share_and_dividends',
            'house_and_buildings',
            'properties',
            'transportations',
            'debts'
        ])
            ->where('user_id','=',auth()->user()->id)
            ->where('financial_year_id','=',$year->id)
            ->first();

        $response = ['declaration' => $declaration,'year' => $year->year];

        return response()->json($response,200);
    }

    private function generateAdfNumber($declarationCode,$year): string
    {
        return 'ADF'.'-'.$declarationCode.'-'.$year.'-'.mt_rand(100,999);
    }

}
