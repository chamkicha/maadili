<?php

namespace App\Http\Controllers\RejestaZawadi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\rejesta_zawadi;
use App\Models\rejesta_zawadi_taarifa;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class rejestaZawadiController extends Controller
{
    public function index(){
        try{

            $rejesta = rejesta_zawadi::with([
                        'kiongozi' => function ($qry) {
                            $qry->select('id', 'first_name','middle_name','last_name','nida','phone_number');
                        },
                        'rejesta_zawadi_taarifa.taasisi',
                        'rejesta_zawadi_taarifa.kiongozi_aliyepokea_zawadi' => function ($qry) {
                            $qry->select('id', 'first_name','middle_name','last_name','nida','phone_number');
                        }
                        ])
                    ->where('user_id', '=', auth()->user()->id)
                    ->orderByDesc('id')
                    ->get();

                    $response = [
                        'statusCode' => 200, 
                        'rejesta' => $rejesta,
                        'message' => 'Orodha ya Rejesta za Zawadi.'
                ];
        
            return response()->json($response, 200);



        } catch (Exception $error) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'Tatizo la Kimtandao.',
                'error' => $error,
            ]);
        }

    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'financial_year_id' => 'required|integer',
            
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        try{

            $check = rejesta_zawadi::where([
                                        'user_id' => auth()->user()->id,
                                        'financial_year_id' => $request->input('financial_year_id'),
                                    ])->first();

            if($check === null){
                $rejesta = rejesta_zawadi::updateOrCreate([
                                                'secure_token' => Str::uuid(),
                                                'user_id' => auth()->user()->id,
                                                'financial_year_id' => $request->input('financial_year_id'),
                                            ]);
            }else{
                $rejesta = $check;
            }
            $rejesta_zawadi_taarifa_create = $this->rejesta_zawadi_taarifa_create($request->data,$rejesta->id);

            $response = [
                'statusCode' => 200, 
                'rejesta_id' => $rejesta->id,
                'message' => 'Umefanikiwa kusajili Rejesta ya Zawadi.'
            ];
        
            return response()->json($response, 200);



        } catch (Exception $error) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'Tatizo la Kimtandao.',
                'error' => $error,
            ]);
        }
        

    }

    public function rejesta_zawadi_taarifa_create($datas,$rejesta_id){
        
        foreach($datas as $data){
            $validator = Validator::make($data, [
                'kiongozi_id' => 'required|integer',
                'jina_aliyetoa_zawadi' => 'required|string',
                'maelezo_zawadi' => 'required|string',
                'tar_kupokea_zawadi' => 'required|string',
                'mazingira_ilipopokelewa' => 'required|string',
                'tar_kutoa_tamko' => 'required|string',
                'tar_kukabidhi_zawadi' => 'required|string',
                'zawadi_ilivyotumika' => 'required|string',
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors());
            }

            $rejesta = rejesta_zawadi_taarifa::updateOrCreate([
                'secure_token' => Str::uuid(),
                'rejesta_id' => $rejesta_id,
                'kiongozi_id' => $data['kiongozi_id'],
                'jina_aliyetoa_zawadi' => $data['jina_aliyetoa_zawadi'],
                'maelezo_zawadi' => $data['maelezo_zawadi'],
                'thamani_zawadi' => $data['thamani_zawadi'],
                'tar_kupokea_zawadi' => $data['tar_kupokea_zawadi'],
                'mazingira_ilipopokelewa' => $data['mazingira_ilipopokelewa'],
                'tar_kutoa_tamko' => $data['tar_kutoa_tamko'],
                'tar_kukabidhi_zawadi' => $data['tar_kukabidhi_zawadi'],
                'zawadi_ilivyotumika' => $data['zawadi_ilivyotumika'],
                'taasisi_id' => $data['taasisi_id']
            ]);

        }


    }

    public function edit($rejesta_id){
        try{

            $rejesta_data = rejesta_zawadi_taarifa::where(['rejesta_id' => $rejesta_id])->get();


            $response = [
                'statusCode' => 200, 
                'rejesta_data' => $rejesta_data, 
                'message' => 'Orodha ya Taarifa za Rejesta.'
            ];
        
            return response()->json($response, 200);



        } catch (Exception $error) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'Tatizo la Kimtandao.',
                'error' => $error,
            ]);
        }

    }

    public function update(Request $request,$rejesta_data_id){
        
        try{

            $check = rejesta_zawadi_taarifa::where(['id' => $rejesta_data_id])->first();

            if ($check) {
                $check->update([
                    'kiongozi_id' => $request->kiongozi_id,
                    'jina_aliyetoa_zawadi' => $request->jina_aliyetoa_zawadi,
                    'maelezo_zawadi' => $request->maelezo_zawadi,
                    'thamani_zawadi' => $request->thamani_zawadi,
                    'tar_kupokea_zawadi' => $request->tar_kupokea_zawadi,
                    'mazingira_ilipopokelewa' => $request->mazingira_ilipopokelewa,
                    'tar_kutoa_tamko' => $request->tar_kutoa_tamko,
                    'tar_kukabidhi_zawadi' => $request->tar_kukabidhi_zawadi,
                    'zawadi_ilivyotumika' => $request->zawadi_ilivyotumika,
                    'taasisi_id' => $request->taasisi_id,
                ]);
            }

            $response = [
                'statusCode' => 200, 
                'message' => 'Umefanikiwa kusasisha taarifa za Rejesta za Zawadi.'
            ];
        
            return response()->json($response, 200);



        } catch (Exception $error) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'Tatizo la Kimtandao.',
                'error' => $error,
            ]);
        }

    }



    public function delete($rejesta_data_id){
        
        try{

            $check = rejesta_zawadi_taarifa::where(['id' => $rejesta_data_id])->first();

            if ($check) {
                $check->delete();
            }

            $response = [
                'statusCode' => 200, 
                'message' => 'Umefanikiwa kufuta taarifa ya Rejesta za Zawadi.'
            ];
        
            return response()->json($response, 200);



        } catch (Exception $error) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'Tatizo la Kimtandao.',
                'error' => $error,
            ]);
        }

    }
}
