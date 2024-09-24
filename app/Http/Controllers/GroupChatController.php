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
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class GroupChatController extends Controller
{
    public function index($groupChatId)
    {
        $groupChat = GroupChat::with(['participants', 'messages.attachments', 'messages.sender', 'conversation'])
            ->findOrFail($groupChatId);

        // $messages = $groupChat->messages;
        $messages = $groupChat->messages->map(function ($message) {
            return [
                'id' => $message->id,
                'message' => $message->message,
                'attachments' => $message->attachments,
                'sender_id' => $message->sender_id,
                'sender' => new UserResource($message->sender),
                'created_at' => $message->created_at,
            ];
        });

        $authUser = auth()->user();

        $conversation = $groupChat->conversation;

        return Inertia::render('Chat/GroupChat', [
            'groupChat' => new GroupChatResource($groupChat),
            'messages' => $messages,
            'conversation' => $conversation,
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

        Conversation::create([
            'group_id' => $groupChat->id,
        ]);

        // return response()->json($groupChat);
        return response()->json([
            'groupChatUrl' => route('group-chats.index', ['groupChat' => $groupChat->id])
        ]);
    }

    public function fetchUsers(Request $request)
    {
        $userId = Auth::id();
        $groupId = $request->input('groupId');

        $users = User::where('id', '!=', $userId)
            ->whereNotIn('id', function ($query) use ($groupId) {
                $query->select('user_id')
                    ->from('group_chat_participants')
                    ->where('group_chat_id', $groupId);
            })
            ->get();

        return response()->json(UserResource::collection($users));
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

        $transformedMessages = $messages->map(function ($message) {
            return [
                'id' => $message->id,
                'message' => $message->message,
                'created_at' => $message->created_at,
                'attachments' => $message->attachments,
                'sender_id' => $message->sender_id,
                'sender' => new UserResource($message->sender),
            ];
        });

        return response()->json($transformedMessages);
    }

    public function update(Request $request, GroupChat $groupChat)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($groupChat->photo) {
                Storage::delete('public/' . $groupChat->photo);
            }

            $imagePath = $request->file('photo')->store('group_chats/' . $groupChat->id, 'public');
            $validated['photo'] = $imagePath;
        }

        $groupChat->update($validated);

        return response()->json([
            'message' => 'Group chat updated successfully',
            'groupChat' => $groupChat,
        ]);
    }


}
