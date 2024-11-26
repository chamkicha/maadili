<?php

namespace App\Http\Controllers\Auth;

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
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;

class forgotPasswordController extends Controller
{

    public function sendResetPassword(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'username' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }


        $login_type = filter_var($request->input('username'), FILTER_SANITIZE_NUMBER_INT);


        $phone_to_check = str_replace("-", "", $login_type);


        if (strlen($phone_to_check) >= 9 && strlen($phone_to_check) <= 13) {

            $user_name =  "phone_number";
        } elseif (strlen($phone_to_check) > 13) {

            $user_name = "nida";
        }
        else{

            return response()
                ->json(['statusCode' => 401, 'message' => 'Jina la mtumiaji ambayo umeingiza sio namba ya simu na pia sio nida namba, tafadhali ingiza jina la mtumiaji sahihi.'], 401);
        }

        $request->merge([
            $user_name => $request->input('username')
        ]);

        try {

            // $this->checkTooManyFailedAttempts();

            $input = $request->only('username');

            $user = User::where($user_name,'=',$input)->first();

            if ($user == null){

                $response = ['statusCode' => 400, 'message' => 'Namba ya simu au NIDA ulioingiza haipo kwenye mfumo, tafadhali ingiza ilio sahihi'];
                return response()->json($response,200);
            }
            $user->password = Hash::make(strtoupper($user->last_name));
            $user->is_password_forget = true;
            $user->save();

            $this->sendMessage($user);

            $response = ['statusCode' => 200,'message' => 'Ujumbe wa kubadilisha neno siri umetumwa kikamilifu kwenye namba yako ya simu'];
            return response($response, 200);

        } catch (Exception $error) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'Error occurred while Forget password.',
                'error' => $error,
            ]);
        }
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

        $response = ['statusCode' => 200,'message' => 'Umefanikiwa kubadili neno siri kikamilifu'];
        return response($response, 200);
    }

    private function sendMessage($user) {

        $url ='http://api.maadili.go.tz:9003/emis/send-sms';
        $message = "Ndugu kiongozi, ".$user->first_name.' '.$user->middle_name.' '.$user->last_name ." umefanikiwa kubadili nywila. Tafadhali tumia nywila: ".strtoupper($user->last_name)." kuingia kwenye mfumo.Taarifa hizi ni za siri.";
        $response = Http::asForm()->post($url, [
            'message' => $message,
            'phoneNumber' => $user->phone_number,
        ]);
    }


    public function checkTooManyFailedAttempts(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 3)) {
            return;
        }

        throw new ValidationException('IP address banned. Too many Forget password attempts.');
    }


    public function sendOTP(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'username' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }


        $login_type = filter_var($request->input('username'), FILTER_SANITIZE_NUMBER_INT);


        $phone_to_check = str_replace("-", "", $login_type);


        if (strlen($phone_to_check) >= 9 && strlen($phone_to_check) <= 13) {

            $user_name =  "phone_number";
        } elseif (strlen($phone_to_check) > 13) {

            $user_name = "nida";
        }
        else{

            return response()
                ->json(['statusCode' => 401, 'message' => 'Jina la mtumiaji ambayo umeingiza sio namba ya simu na pia sio nida namba, tafadhali ingiza jina la mtumiaji sahihi.'], 401);
        }

        $request->merge([
            $user_name => $request->input('username')
        ]);

        try {

            $input = $request->only('username');

            $user = User::where($user_name,'=',$input)->first();

            if ($user == null){

                $response = ['statusCode' => 400, 'message' => 'Namba ya simu au NIDA ulioingiza haipo kwenye mfumo, tafadhali ingiza ilio sahihi'];
                return response()->json($response,200);
            }

            $otp = random_int(1000, 9999);
            $phno = $user->phone_number;
            // $phno = '255713471801';
            $url ='http://api.maadili.go.tz:9003/emis/send-sms';

            $response = Http::asForm()->post($url, [
                'message' => "Your OTP is: $otp",
                'phoneNumber' => $phno,
            ]);

            // dd($response);

            $response = ['statusCode' => 200,'message' => 'OTP','otp' => $otp];
            return response($response, 200);

        } catch (Exception $error) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'Error occurred while Forget password.',
                'error' => $error,
            ]);
        }
    }

}
