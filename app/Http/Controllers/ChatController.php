<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupChatResource;
use App\Http\Resources\UserResource;
use App\Models\MessageAttachment;
use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;
use App\Models\GroupChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ChatController extends Controller
{
    // public function index(Request $request)
    // {
    //     $user = auth()->user();
    //     $followings = $user->followings()->get();

    //     return Inertia::render('Chat/Home', [
    //         'followings' => UserResource::collection($followings),
    //     ]);
    // }
    // public function index(Request $request)
    // {
    //     $user = auth()->user();

    //     $followings = $user->followings()->get();

    //     $conversations = Conversation::where(function ($query) use ($user) {
    //         $query->where('user_id1', $user->id)
    //             ->orWhere('user_id2', $user->id);
    //     })->get();

    //     $participants = $conversations->map(function ($conversation) use ($user) {
    //         return $conversation->user_id1 === $user->id
    //             ? User::find($conversation->user_id2)
    //             : User::find($conversation->user_id1);
    //     })->unique('id');

    //     $filteredParticipants = $participants->reject(function ($participant) use ($followings) {
    //         return $followings->contains('id', $participant->id);
    //     });

    //     $groupChats = GroupChat::whereHas('participants', function ($query) use ($user) {
    //         $query->where('user_id', $user->id);
    //     })->get();

    //     return Inertia::render('Chat/Home', [
    //         'followings' => UserResource::collection($followings),
    //         'participants' => UserResource::collection($filteredParticipants),
    //         'groupChats' => GroupChatResource::collection($groupChats),
    //     ]);
    // }

    // public function index(Request $request)
    // {
    //     $user = auth()->user();

    //     $messageUsers = Message::where(function ($query) use ($user) {
    //         $query->where('sender_id', $user->id)
    //             ->orWhere('receiver_id', $user->id);
    //     })
    //         ->with(['sender', 'receiver'])
    //         ->latest('created_at')
    //         ->get()
    //         ->map(function ($message) use ($user) {
    //             if ($message->sender_id === $user->id && $message->receiver) {
    //                 return $message->receiver;
    //             } elseif ($message->receiver_id === $user->id && $message->sender) {
    //                 return $message->sender;
    //             }
    //             return null;
    //         })
    //         ->filter()
    //         ->unique('id')
    //         ->values();

    //     $groupChats = GroupChat::whereHas('participants', function ($query) use ($user) {
    //         $query->where('user_id', $user->id);
    //     })->get();

    //     return Inertia::render('Chat/Home', [
    //         'messageUsers' => UserResource::collection($messageUsers),
    //         'groupChats' => GroupChatResource::collection($groupChats),
    //     ]);
    // }

    public function index(Request $request)
    {
        $user = auth()->user();

        $latestMessages = Message::where(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id);
        })
            ->latest('created_at')
            ->get()
            ->unique(function ($message) use ($user) {
                return $message->sender_id === $user->id
                    ? $message->receiver_id
                    : $message->sender_id;
            });

        $messageUsers = $latestMessages->map(function ($message) use ($user) {
            $contact = $message->sender_id === $user->id
                ? $message->receiver
                : $message->sender;

            if ($contact) {
                $contact->last_message = $message->message;
                $contact->last_message_sender_name = $message->sender->name;
            }

            return $contact;
        })->filter();

        $groupChats = GroupChat::whereHas('participants', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        return Inertia::render('Chat/Home', [
            'messageUsers' => UserResource::collection($messageUsers),
            'groupChats' => GroupChatResource::collection($groupChats),
        ]);
    }

    public function getLatestMessages(Request $request)
    {
        $user = auth()->user();

        $latestMessages = Message::where(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id);
        })
            ->latest('created_at')
            ->get()
            ->unique(function ($message) use ($user) {
                return $message->sender_id === $user->id
                    ? $message->receiver_id
                    : $message->sender_id;
            });

        $messageUsers = $latestMessages->map(function ($message) use ($user) {
            $contact = $message->sender_id === $user->id
                ? $message->receiver
                : $message->sender;

            if ($contact) {
                $contact->last_message = $message->message;
                $contact->last_message_sender_name = $message->sender->name;
            }

            return $contact;
        })->filter();

        $groupChats = GroupChat::whereHas('participants', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        return response()->json([
            'messageUsers' => UserResource::collection($messageUsers),
            'groupChats' => GroupChatResource::collection($groupChats),
        ]);
    }

    public function searchFollowings(Request $request)
    {
        $user = auth()->user();

        $query = $request->input('query', '');

        $followings = $user->followings()
            ->where('name', 'like', '%' . $query . '%')
            ->orWhere('surname', 'like', '%' . $query . '%')
            ->orWhere('username', 'like', '%' . $query . '%')
            ->get();

        // return response()->json($followings);
        return response()->json([
            'followings' => UserResource::collection($followings),
        ]);
    }

    public function getConversation(User $user)
    {
        $conversation = Conversation::where(function ($query) use ($user) {
            $query->where('user_id1', auth()->id())
                ->where('user_id2', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('user_id1', $user->id)
                ->where('user_id2', auth()->id());
        })->with('messages.attachments')->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_id1' => auth()->id(),
                'user_id2' => $user->id,
            ]);
        }

        $messages = $conversation->messages;

        return Inertia::render('Chat/Conversation', [
            'conversation' => $conversation,
            'messages' => $messages,
            'user' => new UserResource($user)
        ]);
    }

    public function fetchMessages($conversationId)
    {
        $conversation = Conversation::with('messages.attachments')
            ->where('id', $conversationId)
            ->first();

        if (!$conversation) {
            return response()->json(['message' => 'Conversation not found'], 404);
        }

        $messages = $conversation->messages;

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        // Validate the request
        $request->validate([
            'receiver_id' => 'required|integer|exists:users,id',
            'conversation_id' => 'required|integer|exists:conversations,id',
            'message' => 'nullable|string',
            'files.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:20480', // Adjust as needed
        ]);

        // Check if there is neither a message nor files
        if (empty($request->message) && !$request->hasFile('files')) {
            return response()->json(['error' => 'Message or files are required'], 400);
        }

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Create the message
            $message = Message::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $request->receiver_id,
                'conversation_id' => $request->conversation_id,
                'message' => $request->message,
            ]);

            // $message->conversation->update([
            //     'last_message_id' => $message->id,
            // ]);

            Conversation::where('id', $request->conversation_id)->update([
                'last_message_id' => $message->id,
            ]);

            // Handle file uploads if there are any
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    // Store the file and get its path
                    $path = $file->store('message_attachments/' . $message->id, 'public');

                    // Create an entry for each uploaded file
                    MessageAttachment::create([
                        'message_id' => $message->id,
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'mime' => $file->getMimeType(),
                        'size' => $file->getSize(),
                    ]);
                }
            }

            // Commit the transaction
            DB::commit();

            // Optionally, broadcast the message to a channel
            // broadcast(new MessageSent($message))->toOthers();

            return response()->json($message);
        } catch (\Exception $e) {
            // Rollback the transaction on failure
            DB::rollBack();
            return response()->json(['error' => 'Failed to send message'], 500);
        }
    }

    // public function sendGroupMessage(Request $request)
    // {
    //     $message = Message::create([
    //         'message' => $request->message,
    //         'sender_id' => auth()->id(),
    //         'group_id' => $request->group_id,
    //     ]);

    //     GroupChat::where('id', $request->group_id)->update(['last_message_id' => $message->id]);

    //     return response()->json($message);
    // }

    public function sendMessageToGroup(Request $request, GroupChat $group)
    {
        $message = new Message();
        $message->message = $request->message;
        $message->sender_id = auth()->id();
        $message->group_id = $group->id;
        $message->save();

        // Handle file attachments
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // $path = $file->store('message_attachments');
                $path = $file->store('message_attachments/' . $message->id, 'public');
                $message->attachments()->create([
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        // Update last message in the group
        $group->last_message_id = $message->id;
        $group->save();

        return response()->json($message);
    }

}
