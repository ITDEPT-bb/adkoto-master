<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupChatResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\UserResource;
use App\Models\Conversation;
use App\Models\GroupChat;
use App\Models\GroupChatParticipant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class GroupChatController extends Controller
{
    public function index($groupChatId)
    {
        $groupChat = GroupChat::with(['participants', 'messages.attachments'])
            ->findOrFail($groupChatId);

        $messages = $groupChat->messages;

        $authUser = auth()->user();

        return Inertia::render('Chat/GroupChat', [
            'groupChat' => new GroupChatResource($groupChat),
            'messages' => $messages,
            'authUser' => new UserResource($authUser),
        ]);
    }


    public function create(Request $request)
    {
        $groupChat = GroupChat::create([
            'name' => $request->name,
            'description' => $request->description,
            'owner_id' => auth()->id(),
        ]);

        GroupChatParticipant::create([
            'group_chat_id' => $groupChat->id,
            'user_id' => auth()->id(),
        ]);

        return response()->json($groupChat);
    }

    public function fetchUsers(Request $request)
    {
        $userId = Auth::id();

        $users = User::where('id', '!=', $userId)
            ->get();

        $user = UserResource::collection($users);

        return response()->json($user);
    }

    public function addParticipant(Request $request, $groupChatId)
    {
        $groupChat = GroupChat::findOrFail($groupChatId);

        foreach ($request->users as $userId) {
            GroupChatParticipant::firstOrCreate([
                'group_chat_id' => $groupChat->id,
                'user_id' => $userId,
            ]);
        }

        return response()->json(['message' => 'Participants added successfully']);
    }

    public function getGroupMessages($groupChatId)
    {
        $groupChat = GroupChat::findOrFail($groupChatId);
        $messages = $groupChat->messages()->with('sender', 'attachments')->get();

        return response()->json($messages);
    }
}
