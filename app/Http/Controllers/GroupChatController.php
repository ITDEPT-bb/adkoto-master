<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupChatResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\UserResource;
use App\Models\Conversation;
use App\Models\Group;
use App\Models\GroupChat;
use App\Models\GroupChatParticipant;
use App\Models\User;
use App\Notifications\AddedToGroupChat;
use App\Notifications\LeaveGroupChat;
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

        $isParticipant = $groupChat->participants()->where('user_id', $user->id)->exists();

        if (!$isParticipant) {
            abort(403, 'Unauthorized');
        }

        $groupChatMembers = $groupChat->participants;

        $notifications = $groupChat->notifications()
            ->where('data->group_id', $groupChatId)
            ->get();

        $notificationMessages = $notifications->map(function ($notification) {
            return [
                'id' => 'notification-' . $notification->id,
                'type' => 'system',
                'message' => $notification->data['message'],
                'created_at' => $notification->created_at,
            ];
        });

        $regularMessages = $groupChat->messages->map(function ($message) {
            $sender = $message->sender()->withTrashed()->first();

            return [
                'id' => $message->id,
                'type' => 'user',
                'message' => $message->message,
                'attachments' => $message->attachments,
                'sender_id' => $message->sender_id,
                'sender' => $sender ? new UserResource($sender) : null,
                'created_at' => $message->created_at,
            ];
        });

        $messages = $notificationMessages->concat($regularMessages)->sortBy('created_at')->values();

        $conversation = $groupChat->conversation;

        return Inertia::render('Chat/GroupChat', [
            'groupChat' => new GroupChatResource($groupChat),
            'messages' => $messages,
            'conversation' => $conversation,
            'authUser' => new UserResource($user),
            'notifications' => $notifications,
            'members' => $groupChatMembers,
        ]);
    }

    public function create(Request $request)
    {
        $groupChat = GroupChat::create([
            'name' => $request->name,
            'description' => $request->description,
            'group_id' => $request->group_id,
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

    public function handleGroupChat($group_id)
    {
        $group = GroupChat::where('group_id', $group_id)->first();

        if ($group) {
            return response()->json(['redirect' => route('group-chats.index', ['groupChat' => $group->id])]);
        }

        return response()->json(['message' => 'No group found'], 404);
    }


    // public function handleGroupChat($group_id)
    // {
    //     $group = GroupChat::where('group_id', $group_id)->first();

    //     if ($group) {
    //         return response()->json(['redirectUrl' => route('group-chats.index', ['groupChat' => $group->id])]);
    //     }

    //     return response()->json(['message' => 'No group found'], 404);
    // }

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
        $user = auth()->user();

        foreach ($request->users as $userId) {
            // $user = User::findOrFail($userId);
            $userToAdd = User::findOrFail($userId);

            GroupChatParticipant::firstOrCreate([
                'group_chat_id' => $groupChat->id,
                'user_id' => $userId,
            ]);

            // $user->notify(new AddedToGroupChat($groupChat, $user));
            // $userToAdd->notify(new AddedToGroupChat($groupChat, $user, $userToAdd));
            $groupChat->notify(new AddedToGroupChat($groupChat, $user, $userToAdd));
        }

        return response()->json(['message' => 'Participants added successfully']);
    }

    public function getGroupMessages($groupChatId)
    {
        $groupChat = GroupChat::findOrFail($groupChatId);

        // $messages = $groupChat->messages()->with('sender', 'attachments')->get();

        $notifications = $groupChat->notifications()
            ->where('data->group_id', $groupChatId)
            ->get();

        $notificationMessages = $notifications->map(function ($notification) {
            return [
                'id' => 'notification-' . $notification->id,
                'type' => 'system',
                'message' => $notification->data['message'],
                'created_at' => $notification->created_at,
            ];
        });

        $regularMessages = $groupChat->messages->map(function ($message) {
            $sender = $message->sender()->withTrashed()->first();

            return [
                'id' => $message->id,
                'type' => 'user',
                'message' => $message->message,
                'attachments' => $message->attachments,
                'sender_id' => $message->sender_id,
                'sender' => $sender ? new UserResource($sender) : null,
                'created_at' => $message->created_at,
            ];
        });

        $messages = $notificationMessages->concat($regularMessages)->sortBy('created_at')->values();

        // $transformedMessages = $messages->map(function ($message) {
        //     return [
        //         'id' => $message->id,
        //         'message' => $message->message,
        //         'created_at' => $message->created_at,
        //         'attachments' => $message->attachments,
        //         'sender_id' => $message->sender_id,
        //         'sender' => new UserResource($message->sender),
        //     ];
        // });

        return response()->json($messages);
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
        $groupChat = GroupChat::findOrFail($groupChatId);

        $user = Auth::user();

        $participant = GroupChatParticipant::where('group_chat_id', $groupChatId)
            ->where('user_id', $user->id)
            ->first();

        if (!$participant) {
            return response()->json(['message' => 'You are not a member of this group chat.'], 404);
        }

        $participant->delete();

        $groupChat->notify(new LeaveGroupChat($groupChat, $user));

        $remainingParticipants = GroupChatParticipant::where('group_chat_id', $groupChatId)->count();

        if ($remainingParticipants == 0) {
            $groupChat = GroupChat::find($groupChatId);
            if ($groupChat) {
                $groupChat->delete();
            }
        }

        // Redirect the user to the chat page
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
