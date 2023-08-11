<?php

namespace App\Http\Controllers\RejestaZawadi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\rejesta_zawadi;

class rejestaZawadiController extends Controller
{
    public function index(){
        try{

        $rejesta = rejesta_zawadi::with('rejesta_zawadi_taarifa')
                                    ->where('user_id', '=', auth()->user()->id)
                                    ->orderByDesc('id')
                                    ->get();
            if($rejesta){

                $response = [
                    'statusCode' => 200, 
                    'rejesta' => $rejesta,
                    'message' => 'Orodha ya Rejesta za Zawadi.'
               ];
    
                return response()->json($response, 200);

            }

            $response = [
                        'statusCode' => 400, 
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
}
