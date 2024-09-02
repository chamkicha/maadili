<?php

namespace App\Http\Controllers;

use Exception;
use App\EsbHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class govesbController extends Controller
{
    public function exchangeRate(Request $request)
    {
        $apiCode = "e5m4osCo";
        // $payload = [
        //     'registration_no' => '545397',
        //     'institution' => 'BAS-7d7994f4-a9e9-43b8-8a53-b88c8358867e',
        // ];

        $helper = new EsbHelper();

        $esbResponse = $helper->requestData($apiCode, null, "json");

        // return 'test';
        return $esbResponse;

    }

    public function nectaResult(Request $request)
    {
        $apiCode = "c8Nd9Mi6";
        $nectaPayload = [
            "exam_year" => $request->exam_year,
            "exam_id" => 1,
            "index_number" =>  $request->index_number,
            "api_key" => '$2y$10$V0Q9s.CWtGnRtPQRTVEP3OFv4.UUij4fyQMlRH7ON41Z5GRx5oOnS'
        ];

        $helper = new EsbHelper();
        $esbResponse = $helper->requestData($apiCode, $nectaPayload, "json");
        return $esbResponse;
    }

    // public function brela(Request $request)
    // {
    //     $apiCode = "OniQ3ahV";

    //     $payload = [
    //         'requestdata' => [
    //             "RegistrationNumber" => $request->RegistrationNumber,
    //             "ApiKey" => "TEST-400c0a84-da97-46a8-b93cbca",
    //             "EntityType" => 1
    //         ]
    //     ];

    //     $helper = new EsbHelper();
    //     $esbResponse = $helper->requestData($apiCode, $payload, "json");
    //     return $esbResponse;
    // }

    public function brela(Request $request)
    {
        Log::info("brelaRequest - " . json_encode($request->all()));

        $validator = Validator::make($request->all(), [
            'RegistrationNumber' =>  ['required','integer'],
            'EntityType' =>  ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'statusCode' => 401,
                'message' => 'validation error',
                'fields' => $validator->errors(),
                'error' => true,
            ]);
        }

        try{

            $apiCode = "A6HbQ9oj";

            $payload = [
                'requestdata' => [
                    "RegistrationNumber" => (int)$request->RegistrationNumber,
                    "ApiKey" => "TEST-400c0a84-da97-46a8-b93cbcabnmz",
                    "EntityType" => $request->EntityType
                ]
            ];

            $helper = new EsbHelper();
            $esbResponse = $helper->requestData($apiCode, $payload, "json");

            if($esbResponse['success']){
                return response()->json([
                    'statusCode' => 400,
                    'message' => 'Success',
                    'data' => $esbResponse['esbBody'],
                ]);
            }else{
                return response()->json([
                    'statusCode' => 401,
                    'message' => $esbResponse['message'],
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

    public function brelaListCompaniesBusinessByNIN(Request $request)
    {
        Log::info("brelaRequest - " . json_encode($request->all()));

        $validator = Validator::make($request->all(), [
            'NIN' =>  ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'statusCode' => 401,
                'message' => 'validation error',
                'fields' => $validator->errors(),
                'error' => true,
            ]);
        }

        try{

            $apiCode = "OniQ3ahV";

            $payload = [
                'ninrequestdata' => [
                    "NIN" => $request->NIN,
                    "ApiKey" => "TEST-400c0a84-da97-46a8-b93cbcabnmz"
                ]
            ];

            $helper = new EsbHelper();
            $esbResponse = $helper->requestData($apiCode, $payload, "json");

            if($esbResponse['success']){
                return response()->json([
                    'statusCode' => 400,
                    'message' => 'Success',
                    'data' => $esbResponse['esbBody'],
                ]);
            }else{
                return response()->json([
                    'statusCode' => 401,
                    'message' => $esbResponse['message'],
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

    public function NapaNormalRequest(Request $request)
    {
        Log::info("NapaNormalRequest - " . json_encode($request->all()));

        $validator = Validator::make($request->all(), [
            'nin' =>  ['required'],
            'tin' =>  ['required'],
            'phone' =>  ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'statusCode' => 401,
                'message' => 'validation error',
                'fields' => $validator->errors(),
                'error' => true,
            ]);
        }

        try{

        $apiCode = "2uqzVCtW";
        $payload = [
            "nin" => $request->nin,
            "tin" => $request->tin,
            "phone" => $request->phone,
            "clientCode" => 92

        ];

        $helper = new EsbHelper();
        $esbResponse = $helper->requestData($apiCode, $payload, "json");

        if($esbResponse['success']){
            return response()->json([
                'statusCode' => 400,
                'message' => 'Success',
                'data' => $esbResponse['esbBody'],
            ]);
        }else{
            return response()->json([
                'statusCode' => 401,
                'message' => $esbResponse['message'],
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

    public function NapaHirearchyRequest(Request $request)
    {
        Log::info("NapaHirearchyRequest - " . json_encode($request->all()));

        $validator = Validator::make($request->all(), [
            'levelId' =>  ['required','integer'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'statusCode' => 401,
                'message' => 'validation error',
                'fields' => $validator->errors(),
                'error' => true,
            ]);
        }

        try{

            $apiCode = "95NHCdwx";
            $payload = [
                "id" => (int)$request->ID,
                "levelId" => (int)$request->levelId,
                "clientCode" => 92
            ];

            $helper = new EsbHelper();
            $esbResponse = $helper->requestData($apiCode, $payload, "json");

            if($esbResponse['success']){
                return response()->json([
                    'statusCode' => 400,
                    'message' => 'Success',
                    'data' => $esbResponse['esbBody'],
                ]);
            }else{
                return response()->json([
                    'statusCode' => 401,
                    'message' => $esbResponse['message'],
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

    public function OTRMISNormalRequest(Request $request)
    {
        Log::info("OTRMISNormalRequest - " . json_encode($request->all()));

        $validator = Validator::make($request->all(), [
            'page_size' =>  ['required','integer'],
            'page_number' =>  ['required','integer'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'statusCode' => 401,
                'message' => 'validation error',
                'fields' => $validator->errors(),
                'error' => true,
            ]);
        }

        try{
        $apiCode = "UhHhhr8d";
        $payload = [
            "PAGESIZE" => (int)$request->page_size,
            "PAGENUMBER" => (int)$request->page_number,
        ];

        $helper = new EsbHelper();
        $esbResponse = $helper->requestData($apiCode, $payload, "json");

            if($esbResponse['success']){
                return response()->json([
                    'statusCode' => 400,
                    'message' => 'Success',
                    'data' => $esbResponse['esbBody'],
                ]);
            }else{
                return response()->json([
                    'statusCode' => 401,
                    'message' => $esbResponse['message'],
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

    public function HCMISEmployeesRequest(Request $request)
    {
        Log::info("HCMISEmployeesRequest - " . json_encode($request->all()));

        $validator = Validator::make($request->all(), [
            'page_size' =>  ['required','integer'],
            'page_number' =>  ['required','integer'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'statusCode' => 401,
                'message' => 'validation error',
                'fields' => $validator->errors(),
                'error' => true,
            ]);
        }

        try{
        if (env('APP_ENV') === 'production') {
        $apiCode = "JTVDQUuj";
        }
        else{
            $apiCode = "n2hyG8i1";
        }
        $payload = [
            "page_size" => (int)$request->page_size,
            "page_number" => (int)$request->page_number,
        ];

        $helper = new EsbHelper();
        $esbResponse = $helper->requestData($apiCode, $payload, "json");

            if($esbResponse['success']){
                return response()->json([
                    'statusCode' => 400,
                    'message' => 'Success',
                    'data' => $esbResponse['esbBody'],
                ]);
            }else{
                return response()->json([
                    'statusCode' => 401,
                    'message' => $esbResponse['message'],
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



}
