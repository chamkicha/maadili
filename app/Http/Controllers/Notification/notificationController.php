<?php

namespace App\Http\Controllers\Notification;

use App\Models\Es_contact;
use App\Models\Instruction;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class notificationController extends Controller
{
    public function showNotifications(): JsonResponse
    {

        $notifications = Notification::with([
            'category'
        ])
            ->get();

        $response = ['notifications' => $notifications];

        return response()->json($response,200);
    }
 public function contacts(): JsonResponse
    {
        $es_contacts =Es_contact::with('zone')->get();
        $response = ['es_contact' => $es_contacts];
        return response()->json($response,200);
    }
    public function instructions(): JsonResponse
    {
        $instructions =Instruction::get();
        $response = ['instructions' => $instructions];
        return response()->json($response,200);
    }
}
