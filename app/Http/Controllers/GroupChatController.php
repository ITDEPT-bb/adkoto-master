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
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class GroupChatController extends Controller
{
    // public function index($groupChatId)
    // {
    //     $groupChat = GroupChat::with(['participants', 'messages.attachments', 'messages.sender', 'conversation'])
    //         ->findOrFail($groupChatId);

    //     $messages = $groupChat->messages->map(function ($message) {
    //         return [
    //             'id' => $message->id,
    //             'message' => $message->message,
    //             'attachments' => $message->attachments,
    //             'sender_id' => $message->sender_id,
    //             'sender' => new UserResource($message->sender),
    //             'created_at' => $message->created_at,
    //         ];
    //     });

    //     $authUser = auth()->user();

    //     $conversation = $groupChat->conversation;

    //     return Inertia::render('Chat/GroupChat', [
    //         'groupChat' => new GroupChatResource($groupChat),
    //         'messages' => $messages,
    //         'conversation' => $conversation,
    //         'authUser' => new UserResource($authUser),
    //     ]);
    // }

    public function index($groupChatId)
    {
        $user = auth()->user();

        // Retrieve group chat with relationships and handle soft-deleted users
        $groupChat = GroupChat::with([
            'participants' => function ($query) {
                // Exclude soft-deleted participants
                $query->whereNull('deleted_at');
            },
            'messages.attachments',
            'messages.sender',
            'conversation'
        ])
            ->findOrFail($groupChatId);

        // Check if the user is a participant in the group chat
        $isParticipant = $groupChat->participants()->where('user_id', $user->id)->exists();

        if (!$isParticipant) {
            abort(403, 'Unauthorized');
        }

        // Map messages for the response, excluding soft-deleted senders
        $messages = $groupChat->messages->map(function ($message) {
            // Check if the sender is soft-deleted
            $sender = $message->sender()->withTrashed()->first(); // Include trashed users to check soft-deleted status

            return [
                'id' => $message->id,
                'message' => $message->message,
                'attachments' => $message->attachments,
                'sender_id' => $message->sender_id,
                'sender' => $sender ? new UserResource($sender) : null,  // Handle soft-deleted sender
                'created_at' => $message->created_at,
            ];
        });

        $conversation = $groupChat->conversation;

        return Inertia::render('Chat/GroupChat', [
            'groupChat' => new GroupChatResource($groupChat),
            'messages' => $messages,
            'conversation' => $conversation,
            'authUser' => new UserResource($user),
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
            'is_admin' => true,
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

    public function leaveGroup(Request $request, $groupChatId)
    {
        $user = Auth::user();

        $participant = GroupChatParticipant::where('group_chat_id', $groupChatId)
            ->where('user_id', $user->id)
            ->first();

        if (!$participant) {
            return response()->json(['message' => 'You are not a member of this group chat.'], 404);
        }

        // Delete the participant record
        $participant->delete();

        // return response()->json(['message' => 'You have left the group chat successfully.']);
        return Redirect::to('/chat');
    }

    public function removeUser(Request $request, $groupChatId)
    {
        $admin = Auth::user();
        $userId = $request->input('userId');

        $isAdmin = GroupChatParticipant::where('group_chat_id', $groupChatId)
            ->where('user_id', $admin->id)
            ->where('is_admin', true)
            ->exists();

        if (!$isAdmin) {
            return response()->json(['message' => 'Only admins can remove members from this group chat.'], 403);
        }

        $participant = GroupChatParticipant::where('group_chat_id', $groupChatId)
            ->where('user_id', $userId)
            ->first();

        if (!$participant) {
            return response()->json(['message' => 'The user is not a member of this group chat.'], 404);
        }

        $participant->delete();

        // return response()->json(['message' => 'User removed from the group chat successfully.']);
        return response()->json([
            'message' => 'User removed from the group chat successfully.',
            'redirect' => route('group-chats.index', ['groupChat' => $groupChatId])
        ]);
    }

}
