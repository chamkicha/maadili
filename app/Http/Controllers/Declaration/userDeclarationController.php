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
use App\Models\User_declaration;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use stdClass;

class userDeclarationController extends Controller
{
    public function declarations(): JsonResponse
    {

        $declaration_window = Asset_declaration_window::with([
            'declarations' => function ($query) {
                $query->select('id', 'secure_token', 'type');
            }
        ])
            ->where('is_active', '=', true)
            ->select('id', 'declaration_type_id', 'is_active')
            ->get();

//        $declarations = Declaration_type::get();

        $response = ['declaration_window' => $declaration_window];

        return response()->json($response, 200);

    }

    public function declarationForm($secure_token): JsonResponse
    {
        $declaration = Declaration_type::with([
            'sections'
        ])
            ->where('secure_token', '=', $secure_token)
            ->first();

        $response = ['declaration' => $declaration];

        return response()->json($response, 200);
    }

    public function sectionRequirementsForm($secure_token): JsonResponse
    {

        $section = Section::with([
            'requirements' => function($query){
               $query->with([
                   'requirement' => function($qry){
                      $qry->select('id','label','field_name','field_type');
                   }
               ])
                   ->select('id','secure_token','section_id','requirement_id');
            }
        ])
            ->where('secure_token','=',$secure_token)
            ->first();

        $response = ['section' => $section];

        return response()->json($response, 200);
    }

    public function declarationSubmission(Request $request): JsonResponse
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

            $year = Financial_year::where('is_active', '=', true)->first();

            $check = User_declaration::where('user_id', '=', auth()->user()->id)
                ->where('financial_year_id', '=', $year->id)
                ->where('declaration_type_id', '=', $declaration->id)
                ->first();

            $sections = $request->input('sections');

            if ($check == null) {

                $user_declaration = User_declaration::create([
                    'secure_token' => Str::uuid(),
                    'user_id' => auth()->user()->id,
                    'declaration_type_id' => $declaration->id,
                    'adf_number' => $this->generateAdfNumber($declaration->declaration_code, $year->year),
                    'financial_year_id' => $year->id,
                    'flag' => $request->input('flag')
                ]);

                return $this->insertSections($sections, $user_declaration);

            }

            return $this->insertSections($sections, $check);
        } catch (Exception $error) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'Something went wrong.',
                'error' => $error,
            ]);
        }


    }

    public function previewAdf(): JsonResponse
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
                $query->select('id', 'file_number', 'first_name', 'middle_name', 'last_name', 'nida', 'phone_number');
            },
        ])
            ->where('user_id', '=', auth()->user()->id)
            ->where('financial_year_id', '=', $year->id)
            ->first();

        foreach ($declaration->declaration_type->sections as $section) {

            $data = DB::table(strtolower($section->table_name))
                ->get();

            $section->section_data = $data;
        }

        $response = ['declaration' => $declaration, 'year' => $year->year];

        return response()->json($response, 200);
    }

    public function downloadAdf(Request $request): JsonResponse
    {

        $user_declaration = $request->input('user_declaration');

        $download = Declaration_download::create([
            'secure_token' => Str::uuid(),
            'downloader_secure_token' => auth()->user()->secure_token,
            'user_declaration_id' => $user_declaration,
            'password' => encrypt($this->generateADFPassword())
        ]);

        $response = ['password' => decrypt($download->password)];

        return response()->json($response, 200);
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
    private function insertSections(mixed $sections, $check): JsonResponse
    {

        $array = [];

        foreach ($sections as $section) {


            $table = strtolower($section['section']['table']);
            if (count($section['section']['data']) > 0) {

                foreach ($section['section']['data'] as $values) {

                    $new_object = new stdClass();
                    $object = new stdClass();
                    $object->user_declaration_id = $check->id;
                    foreach ($values as $key => $value) {
                        $object->$key = $value;
                        $new_object = $object;
                    }

                    $array[] = $new_object;

                    $encode = json_encode($array, 1);
                    $row = json_decode($encode, true);

                    DB::table($table)->insert($row);

                }

            }
        }

        $response = ['statusCode' => 200, 'message' => 'Tamko lako limetumwa kikamilifu'];

        return response()->json($response);
    }

}
