<?php

namespace App\Http\Controllers\Notification;

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
}
