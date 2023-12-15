<?php

namespace App\Http\Controllers\Family;

use App\Http\Controllers\Controller;
use App\Models\Family_member;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User_declaration;
use App\Models\Financial_year;
use App\Models\UserDeclarationsLookup;
use App\Models\Declaration_section;
use Illuminate\Support\Facades\Log;


class familyMemberController extends Controller
{
    public function addFamilyMember(Request $request): JsonResponse
    {

        // $validator = Validator::make($request->all(), [
        //     'family_member_type' => 'required|integer',
        //     'sex' => 'required|integer',
        //     'first_name' => 'required|string',
        //     'middle_name' => 'required|string',
        //     'last_name' => 'required|string',
        //     'date_of_birth' => 'required|string',
        //     'occupation' => 'nullable',
		//     'nida'=>'nullable',
		//     'tin_number'=>'nullable'
        // ]);

        // if ($validator->fails()) {
        //     return response()->json($validator->errors());
        // }

      // $id = $request->input('id');

        $member = Family_member::updateOrCreate([
            'secure_token' => Str::uuid(),
            'user_id' => auth()->user()->id,
            'family_member_type_id' => $request->input('family_member_type_id'),
            'sex_id' => $request->input('sex_id'),
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'date_of_birth' => $request->input('date_of_birth'),
            'occupation' => $request->input('occupation'),
	        'nida' => $request->input('nida'),
            'tin_number' => $request->input('tin_number'),
            'phone_no' => $request->input('phone_no'),
            'taasisi_id' => $request->input('taasisi_id'),
            'taasisi_other' => $request->input('taasisi_other'),
            'tarehe_ya_kuajiriwa' => $request->input('tarehe_ya_kuajiriwa'),



        ]);

        $add_to_user_decralation_lookup = $this->addUserDeclaration($member);

        if($request->family_member_type_id == 1 || $request->family_member_type_id == 2){
            $createMenuLookup = createMenuLookup('stage_two');

        }

        $response =  ['statusCode' => 200, 'message' => 'Umefanikiwa kumsajili mwanafamilia/mtegemezi wako kwenye dirisha lako'];
        return response()->json($response);
    }

    public function addUserDeclaration($member){

        $year = Financial_year::where('is_active', '=', true)->first();
        $User_declarations = User_declaration::where('user_id', '=', auth()->user()->id)
                            ->where('financial_year_id', '=', $year->id)
                            ->get();

        if($User_declarations){
            foreach($User_declarations as $User_declaration){


                $Declaration_section = Declaration_section::where('declaration_type_id',$User_declaration->declaration_type_id)->get();

                $pl_lookup_create = UserDeclarationsLookup::create([
                    'pl_id' => auth()->user()->id,
                    'family_member_id' => $member->id,
                    'status_id' => '0',
                    'user_declaration_id' => $User_declaration->id,
                    'declaration_section_count' => $Declaration_section->count(),
                    'is_pl' => '1',

                ]);
            }
        }
    }

    public function getFamilyMembers(): JsonResponse
    {

        $members = Family_member::with(['member_type','genderName','taasisi'])
        ->where('user_id','=',auth()->user()->id)
        ->where('status_id','=',1)
        ->get();

        $response =  ['statusCode' => 200, 'members' => $members ];
        return response()->json($response);
    }

    public function editFamilyMember($id): JsonResponse
    {

        $member = Family_member::where('id','=',$id)->first();

        $response =  ['statusCode' => 200, 'member' => $member ];
        return response()->json($response);
    }

    public function updateFamilyMember(Request $request, $id): JsonResponse
    {
        // Log::debug($request);


        $member = Family_member::where('id', $id)
                    ->update([
                        'user_id' => auth()->user()->id,
                        'family_member_type_id' => $request->input('family_member_type_id'),
                        'sex_id' => $request->input('sex_id'),
                        'first_name' => $request->input('first_name'),
                        'middle_name' => $request->input('middle_name'),
                        'last_name' => $request->input('last_name'),
                        'date_of_birth' => $request->input('date_of_birth'),
                        'occupation' => $request->input('occupation'),
                        'nida' => $request->input('nida'),
                        'tin_number' => $request->input('tin_number'),
                        'phone_no' => $request->input('phone_no'),
                        'taasisi_id' => $request->input('taasisi_id'),
                        'taasisi_other' => $request->input('taasisi_other'),
                        'tarehe_ya_kuajiriwa' => $request->input('tarehe_ya_kuajiriwa'),
                    ]);

        $response =  ['statusCode' => 200, 'message' => 'Umefanikiwa kufanya mabadiliko ya mwanafamilia/mtegemezi wako kwenye dirisha lako'];
        return response()->json($response);

    }

    public function deleteFamilyMember($token)
    {
            $member = Family_member::where('secure_token','=',$token)->first();

            if($member->family_member_type_id == 1 || $member->family_member_type_id == 2){
                $menu_lookup = Menu_lookup::where('user_id','=',auth()->user()->id)->first();
                if($menu_lookup){
                    $menu_lookup->stage_two = false;
                    $menu_lookup->save();
                }
            }

            $member->delete();

            return response()->json(['statusCode' => 200, 'message'=>'Umefanikiwa kufuta mwanafamilia/mtegemezi kikamilifu!']);

    }
    public function deactivateFamilyMember(Request $request)
    {
        // Log::debug($request);
        $member = Family_member::where('id','=',$request->user_id)->first();

        if($member){

            if($member->family_member_type_id == 1 || $member->family_member_type_id == 2){
                $menu_lookup = Menu_lookup::where('user_id','=',auth()->user()->id)->first();
                if($menu_lookup){
                    $menu_lookup->stage_two = false;
                    $menu_lookup->save();
                }
            }

            $member->status_id = 0;
            $member->save();

            return response()->json(['statusCode' => 200, 'message'=>'Umefanikiwa kufuta mwanafamilia/mtegemezi kikamilifu!']);
        }else{
            return response()->json(['statusCode' => 400, 'message'=>'Hekuna taarifa za mwanafamilia/mtegemezi!']);

        }

    }
}
