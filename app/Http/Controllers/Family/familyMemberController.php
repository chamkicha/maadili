<?php

namespace App\Http\Controllers\Family;

use App\Http\Controllers\Controller;
use App\Models\Family_member;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
//            'occupation' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

//        $id = $request->input('id');

        $member = Family_member::updateOrCreate(
           [ 'secure_token' => Str::uuid()],
           [ 'user_id' => auth()->user()->id],
           ['family_member_type_id' => $request->input('family_member_type')],
           ['sex_id' => $request->input('sex')],
           ['first_name' => $request->input('first_name')],
           ['middle_name' => $request->input('middle_name')],
           ['last_name' => $request->input('last_name')],
           ['date_of_birth' => $request->input('date_of_birth')],
           ['occupation' => $request->input('occupation')]
        );

        $response =  ['statusCode' => 200, 'message' => 'Umefanikiwa kumsajili mwanafamilia/mtegemezi wako kwenye dirisha lako'];
        return response()->json($response);
    }

    public function getFamilyMembers(): JsonResponse
    {

        $members = Family_member::with([
            'member_type'
        ])
            ->where('user_id','=',auth()->user()->id)
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
}
