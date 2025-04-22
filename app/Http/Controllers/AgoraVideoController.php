<?php

namespace App\Http\Controllers;

use App\Events\DeclineCall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Classes\AgoraDynamicKey\RtcTokenBuilder;
use App\Events\CallEnded;
use App\Events\MakeAgoraCall;
use Illuminate\Support\Facades\Log;

class AgoraVideoController extends Controller
{
    public function token(Request $request)
    {
        $appID = env('AGORA_APP_ID');
        $appCertificate = env('AGORA_APP_CERTIFICATE');
        $channelName = $request->channelName;
        // $uid = Auth::id();
        // $uid = $request->uid;
        $uid = 0;
        $role = RtcTokenBuilder::RolePublisher;
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
            'userToCall' => $request->input('calleeId'),
            'from' => $request->input('callerId'),
            // 'from' => Auth::id(),
            'token' => $request->input('token'),
            // 'channelName' => $request->input('channel_name'),
            'channelName' => $request->channel,
        ];

        broadcast(new MakeAgoraCall($data))->toOthers();

        return response()->json(['success' => true]);
    }

    public function index()
    {
        // return response()->json(['success' => true]);
        return view('agora.index');
    }

    public function declineCall(Request $request)
    {
        broadcast(new DeclineCall(auth()->id(), $request->to_user_id));
        return response()->json(['status' => 'declined']);
    }

    public function endCall(Request $request)
    {
        broadcast(new CallEnded(auth()->id(), $request->to_user_id));
        return response()->json(['message' => 'Call ended']);
    }
}
