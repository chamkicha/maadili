<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendEmailToRegisteredUser;
use App\Models\Application;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthenticationController extends Controller
{

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(),[
            'username' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $login_type = filter_var($request->input('username'), FILTER_VALIDATE_EMAIL )
            ? 'email'
            : 'nida';

        $request->merge([
            $login_type => $request->input('username')
        ]);

        if (!Auth::attempt($request->only($login_type, 'password')))
        {
            return response()
                ->json(['statusCode' => 401, 'message' => 'Unauthorized'], 401);
        }

        $user = User::where('nida', $request->username)
            ->orWhere('email', $request->username)
            ->firstOrFail();

//        if ($user->verified_at == null){
//
//            return response()->json(['statusCode' => 401, 'message' => 'Samahani hakiki barua pepe yako ili uweze kuendelea'], 401);
//        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['statusCode' => 200, 'message' => 'Hi '.$user->first_name.' '.$user->middle_name.' '.$user->last_name.', karibu kwenye ODS','user' => $user->nida,'access_token' => $token, 'token_type' => 'Bearer', ]);
    }

    public function verifyEmail($token): string
    {

        $verify = User::where('secure_token','=',$token)->first();

        if ($verify == null){

            return "Account yako aitambuliki";
        }

        $verify->verified_at = Carbon::now()->format('Y-m-d H:s:i');
        $verify->save();

        return "Barua pepe yako imethibitishwa kikamilifu, unaweza kurudi kwenye mfumo na kuendelea";
    }

    // method for user logout and delete token
    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();

        $response = [
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ];
        return response()->json($response,200);
    }

    private function basic_email($user) {

        $details = [
            'title' => 'Hakiki Barua pepe yako',
            'body' => 'Samahani unatakiwa kubonyeza kitufe apo chini ili kuthibitisha uwalali wa barua pepe yako',
            'token' => $user->secure_token
        ];

        Mail::to($user->email)->send(new SendEmailToRegisteredUser($details));
   }
}
