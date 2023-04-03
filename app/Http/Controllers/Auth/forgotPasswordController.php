<?php

namespace App\Http\Controllers\API\Authentication;

use App\Http\Controllers\Controller;
use App\Mail\SendEmailForPasswordReset;
use App\Mail\SendEmailToRegisteredUser;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class forgotPasswordController extends Controller
{

    public function sendResetLinkResponse(Request $request)
    {

        $input = $request->only('email');

        $user = User::where('email','=',$input)->first();

        if ($user == null){

            $response = ['statusCode' => 0, 'message' => 'Barua pepe ulioingiza haipo kwenye mfumo, tafadhali ingiza barua pepe ilio sahihi'];
            return response()->json($response,200);
        }

        $this->email($user);

        $response = ['statusCode' => 1,'message' => 'Ujumbe wa kubadilisha neno siri umetumwa kikamilifu kwenye barua pepe yako'];
        return response($response, 200);
    }

    public function sendResetResponse(Request $request){

        $input = $request->only([
            'email', 'password', 'password_confirmation']
        );

        $validator = Validator::make($input, [
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);
        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $user = User::where('email','=',$input['email'])->where('email_verified_at','!=', NULL)->first();

        if ($user == null){

            $response = ['statusCode' => 0,'message' => 'Neno siri aliwezi kubadilishwa kwa sababu labda barua pepe yako haijathibishwa au barua pepe yako haipo'];
            return response($response, 200);
        }
        $user->password = Hash::make($request->input('password'));
        $user->save();

        $response = ['statusCode' => 1,'message' => 'Umefanikiwa kubadili neno siri kikamilifu'];
        return response($response, 200);
    }

    private function email($user) {

        $details = [
            'title' => 'Badilisha neno siri',
            'body' => 'Samahani unatakiwa kubonyeza kitufe apo chini ili kuweza kubadili neno siri lako',
        ];

        Mail::to($user->email)->send(new SendEmailForPasswordReset($details));
    }
}
