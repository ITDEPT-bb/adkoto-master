<?php

namespace App\Http\Controllers;
use App\Models\User;
use Inertia\Inertia;
use App\Http\Resources\UserResource;

use Illuminate\Http\Request;

class CallController extends Controller
{
    public function index(User $user, Request $request)
    {
        $appID = env('AGORA_APP_ID');
        if (!$appID) {
            return response()->json(['error' => 'Agora App ID is not configured'], 500);
        }

        $authUser = auth()->user();
        $callerId = $request->query('caller');

        $isCaller = $callerId == $authUser->id;

        // Generate a unique room ID based on the user ID
        $roomId = 'room_' . md5($user->id);

        return Inertia('Call/Index', [
            'roomId' => $roomId,
            'appId' => $appID,
            'isCaller' => $isCaller,
            'user' => new UserResource($user),
        ]);

        
    }
}
