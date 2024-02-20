<?php

namespace App\Http\Controllers\IntegrityPledge;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\integrity_pledge;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class IntegrityPledgeController extends Controller
{
    public function MyListIntegrityPledge(){
        try{
            $integrity_pledge = integrity_pledge::whereIn('approval_status',['RECEIVED','PENDING'])
                                ->where('user_id',auth()->user()->id)
                                ->get();
                if($integrity_pledge){
                    $response = [
                        'statusCode' => 200,
                        'message' => 'Fetch Successfully',
                        'integrity_pledge' => $integrity_pledge,
                        'sections' => []
                    ];

                }else{
                    $response = [
                        'statusCode' => 402,
                        'message' => 'Hakuna Data',
                        'integrity_pledge' => [],
                        'sections' => []
                    ];

                }

            return response()->json($response);

        }catch (Exception $error) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'something went wrong ',
                'error' => $error->getMessage(),
            ]);
        }
    }
}
