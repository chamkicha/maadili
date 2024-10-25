<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use DB;
use Illuminate\Support\Facades\Log;

class AuthenticationController extends Controller
{

    /**
     * @throws ValidationException
     * @throws Exception
     */


     public function login(Request $request): JsonResponse
    {
        // Input validation
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        // Determine login type based on input
        $login_type = filter_var($request->input('username'), FILTER_SANITIZE_NUMBER_INT);
        $phone_to_check = str_replace("-", "", $login_type);

        if (strlen($phone_to_check) >= 9 && strlen($phone_to_check) <= 13) {
            $user_name =  "phone_number";
        } elseif (strlen($phone_to_check) > 13) {
            $user_name = "nida";
        } else {
            return response()->json([
                'statusCode' => 401,
                'message' => 'Taarifa iliyoingizwa si sahihi, tafadhali ingiza taarifa sahihi.'
            ], 200);
        }

        $request->merge([
            $user_name => $request->input('username')
        ]);

        try {
            // Check if the user has attempted too many times
            if ($this->checkTooManyFailedAttempts()) {
                return response()->json([
                    'statusCode' => 401,
                    'message' => 'Ndugu kiongozi, umejaribu mara nyingi sana. Tafadhali jaribu tena baadaye.'
                ], 200);
            }

            
            // Attempt login
            if (!Auth::attempt($request->only($user_name, 'password'))) {
                // Hit rate limiter on failed attempt
                RateLimiter::hit($this->throttleKey(), 300); // Throttle for 5 minutes (300 seconds)

                return response()->json([
                    'statusCode' => 401,
                    'message' => 'Jina la mtumiaji/nywila haviko sawa'
                ], 200);
            }

            // Clear rate limiter on successful login
            RateLimiter::clear($this->throttleKey());

            // Fetch user details and return token
            $user = User::withCount('declarations')->with('Sectiontaarafa.title_name')
                ->where('nida', $request->username)
                ->orWhere('phone_number', $request->username)
                ->firstOrFail();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'statusCode' => 200,
                'message' => 'Hi ' . $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name . ', karibu kwenye ODS',
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);

        } catch (Exception $error) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'Error occurred while logging in: ' . $error->getMessage(),
                'error' => $error,
            ]);
        }
    }


    public function verifyEmail($token): string
    {

        $verify = User::where('secure_token', '=', $token)->first();

        if ($verify == null) {

            return "Account yako haitambuliki";
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
        return response()->json($response, 200);
    }

    private function basic_email($user)
    {

        $details = [
            'title' => 'Hakiki Barua pepe yako',
            'body' => 'Samahani unatakiwa kubonyeza kitufe apo chini ili kuthibitisha uwalali wa barua pepe yako',
            'token' => $user->secure_token
        ];

        Mail::to($user->email)->send(new SendEmailToRegisteredUser($details));
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */

    protected function throttleKey()
    {
        return strtolower(request()->input('username')) . '|' . request()->ip();
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     * @throws Exception
     */
    public function checkTooManyFailedAttempts()
    {
        $throttleKey = $this->throttleKey();

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            // User has reached the maximum number of attempts (3)
            return true;
        }

        return false;
    }

}
