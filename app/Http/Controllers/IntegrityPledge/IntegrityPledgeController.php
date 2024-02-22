<?php

namespace App\Http\Controllers\IntegrityPledge;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\integrity_pledge;
use App\Models\integrity_pledge_approval;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class IntegrityPledgeController extends Controller
{
    public function MyListIntegrityPledge(){
        try{
            $integrity_pledge = integrity_pledge::with('user','title')->whereIn('approval_status',['RECEIVED','PENDING'])
                                ->where('user_id',auth()->user()->id)
                                ->get();

                        $integrity_pledge->map(function ($item) {
                            $item->full_name = $item->user->first_name . ' ' . $item->user->middle_name . ' ' . $item->user->last_name;
                            $item->title_name = $item->title->title_sw;
                            return $item;
                        });

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

    public function listApprovedIntegrity(){
        try{

             $integrity_pledge = integrity_pledge::with('user','title')->where('approval_status','APPROVED')
                                ->where('user_id',auth()->user()->id)
                                ->get();

                    $integrity_pledge->map(function ($item) {
                        $item->full_name = $item->user->first_name . ' ' . $item->user->middle_name . ' ' . $item->user->last_name;
                        $item->title_name = $item->title->title_sw;
                        $item->signed_date = Carbon::parse($item->created_at)->toDateString();
                        $item->signature = $item->user->signature_image;

                        $item->integrity_pledge_approval = integrity_pledge_approval::with(['integrity_pledge', 'staff'])
                            ->where('approval_status', 'APPROVED')
                            ->first();

                        if ($item->integrity_pledge_approval && $item->integrity_pledge_approval->staff) {
                            $item->approver = $item->integrity_pledge_approval->staff->full_name;
                        } else {
                            $item->approver = null;
                        }


                        return $item;
                    });

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



    public function listIntegrityPledge(){
        try{

             $integrity_pledge = integrity_pledge::with('user','title')->where('approval_status','RECEIVED')
                                ->where('user_id',auth()->user()->id)
                                ->get();

                                $integrity_pledge->map(function ($item) {
                                    $item->full_name = $item->user->first_name . ' ' . $item->user->middle_name . ' ' . $item->user->last_name;
                                    $item->title_name = $item->title->title_sw;
                                    return $item;
                                });

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
