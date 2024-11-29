<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Classes\AgoraDynamicKey\RtcTokenBuilder;
use App\Events\MakeAgoraCall;
use Illuminate\Support\Facades\Log;

class AgoraVideoController extends Controller
{
    public function token(Request $request)
    {
        $appID = env('AGORA_APP_ID');
        $appCertificate = env('AGORA_APP_CERTIFICATE');
        $channelName = $request->channelName;
        $uid = Auth::id();
        $role = RtcTokenBuilder::RoleAttendee;
        $expireTimeInSeconds = 3600;
        $currentTimestamp = now()->getTimestamp();
        $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

        $token = RtcTokenBuilder::buildTokenWithUid($appID, $appCertificate, $channelName, $uid, $role, $privilegeExpiredTs);

        return response()->json(['token' => $token]);
    }

    // public function callUser(Request $request)
    // {
    //     $data = [
    //         'userToCall' => $request->user_to_call,
    //         'channelName' => $request->channel_name,
    //         'from' => Auth::id(),
    //     ];

    //     broadcast(new MakeAgoraCall($data))->toOthers();
    // }

    public function callUser(Request $request)
    {
        $data = [
            'userToCall' => $request->input('user_to_call'),
            'from' => Auth::id(),
            'channelName' => $request->input('channel_name'),
        ];

        broadcast(new MakeAgoraCall($data))->toOthers();

        return response()->json(['success' => true]);
    }
}
