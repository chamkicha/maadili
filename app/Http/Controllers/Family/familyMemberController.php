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


class familyMemberController extends Controller
{
    public function addFamilyMember(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'family_member_type' => 'required|integer',
            'sex' => 'required|integer',
            'first_name' => 'required|string',
            'middle_name' => 'required|string',
            'last_name' => 'required|string',
            'date_of_birth' => 'required|string',
            'occupation' => 'nullable',
		    'nida'=>'nullable',
		    'tin_number'=>'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

      // $id = $request->input('id');

        $member = Family_member::updateOrCreate([
            'secure_token' => Str::uuid(),
            'user_id' => auth()->user()->id,
            'family_member_type_id' => $request->input('family_member_type'),
            'sex_id' => $request->input('sex'),
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
        $createMenuLookup = createMenuLookup('stage_two');

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

    public function editFamilyMember($token): JsonResponse
    {

        $member = Family_member::where('secure_token','=',$token)->first();

        $response =  ['statusCode' => 200, 'member' => $member ];
        return response()->json($response);
    }

    public function updateFamilyMember(Request $request, $token): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'family_member_type' => 'required|integer',
            'sex' => 'required|integer',
            'first_name' => 'required|string',
            'middle_name' => 'required|string',
            'last_name' => 'required|string',
            'date_of_birth' => 'required|string',
		    'occupation' => 'nullable',
		    'nida'=>'nullable',
            'tin_number'=>'nullable'

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $member = Family_member::where('secure_token','=',$token)->first();
        $member->family_member_type_id = $request->input('family_member_type');
        $member->sex_id = $request->input('sex');
        $member->first_name = $request->input('first_name');
        $member->middle_name = $request->input('middle_name');
        $member->last_name = $request->input('last_name');
        $member->date_of_birth = $request->input('date_of_birth');
        $member->occupation = $request->input('occupation');
	    $member->nida = $request->input('nida');
        $member->tin_number = $request->input('tin_number');
        $member->phone_no = $request->input('phone_no');
        $member->taasisi_id = $request->input('taasisi_id');
        $member->taasisi_other = $request->input('taasisi_other');
        $member->tarehe_ya_kuajiriwa = $request->input('tarehe_ya_kuajiriwa');
        $member->save();

        $response =  ['statusCode' => 200, 'message' => 'Umefanikiwa kufanya mabadiliko ya mwanafamilia/mtegemezi wako kwenye dirisha lako'];
        return response()->json($response);

    }

    public function deleteFamilyMember($token)
    {
             $member = Family_member::where('secure_token','=',$token)->first();
            $member->delete();
            return response()->json(['statusCode' => 200, 'message'=>'Umefanikiwa kufuta mwanafamilia/mtegemezi kikamilifu!']);
        
    }
}
