<?php

namespace App\Http\Controllers;

use App\Events\GroupChatMessageSent;
use App\Events\MessageSent;
use App\Events\RefreshChatHome;
use App\Http\Resources\GroupChatResource;
use App\Http\Resources\KalakalContactResource;
use App\Http\Resources\UserResource;
use App\Models\Block;
use App\Models\MessageAttachment;
use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;
use App\Models\GroupChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        $latestMessages = Message::with(['sender', 'receiver', 'conversation'])
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
            })
            ->latest('created_at')
            ->get()
            // Only keep messages whose conversation is 'tribekoto'
            ->filter(function ($message) {
                return $message->conversation && $message->conversation->type === 'tribekoto';
            })
            // Get only the latest per contact
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
                $contact->last_message_read_at = $message->read_at;
                $contact->last_message_created_at = $message->created_at;
                $contact->last_message_sender_name = $message->sender->name;
                $contact->last_message_sender_id = $message->sender->id;

                $contact->unread_count = Message::where('sender_id', $contact->id)
                    ->where('receiver_id', $user->id)
                    ->whereHas('conversation', fn($q) => $q->where('type', 'tribekoto'))
                    ->whereNull('read_at')
                    ->count();
            }

            return $contact;
        })->filter();

        // $latestMessages = Message::where(function ($query) use ($user) {
        //     $query->where('sender_id', $user->id)
        //         ->orWhere('receiver_id', $user->id);
        // })
        //     ->latest('created_at')
        //     ->get()
        //     ->unique(function ($message) use ($user) {
        //         return $message->sender_id === $user->id
        //             ? $message->receiver_id
        //             : $message->sender_id;
        //     });

        // $messageUsers = $latestMessages->map(function ($message) use ($user) {
        //     $contact = $message->sender_id === $user->id
        //         ? $message->receiver
        //         : $message->sender;

        //     if ($contact) {
        //         $contact->last_message = $message->message;
        //         $contact->last_message_read_at = $message->read_at;
        //         $contact->last_message_sender_name = $message->sender->name;
        //         $contact->last_message_sender_id = $message->sender->id;
        //         $contact->unread_count = Message::where('sender_id', $contact->id)
        //             ->where('receiver_id', $user->id)
        //             ->whereNull('read_at')
        //             ->count();
        //     }

        //     return $contact;
        // })->filter();

        $groupChats = GroupChat::whereHas('participants', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        $conversations = Conversation::where('type', 'kalakalkoto')
            ->where(function ($query) use ($user) {
                $query->where('user_id1', $user->id)
                    ->orWhere('user_id2', $user->id);
            })
            ->with(['user1', 'user2'])
            ->get();

        // $kalakalUsers = $conversations->map(function ($conversation) use ($user) {
        //     // Identify the other user
        //     $contact = $conversation->user_id1 === $user->id
        //         ? $conversation->user2
        //         : $conversation->user1;

        //     if (!$contact)
        //         return null;

        //     // Get latest message for this conversation
        //     $latestMessage = $conversation->messages()
        //         ->latest()
        //         ->first();

        //     // Attach message info to the user object
        //     $contact->last_message = $latestMessage?->message;
        //     $contact->last_message_read_at = $latestMessage?->read_at;
        //     $contact->last_message_sender_name = $latestMessage?->sender?->name;
        //     $contact->last_message_sender_id = $latestMessage?->sender_id;

        //     // Count unread messages from this contact to the current user
        //     $contact->unread_count = $conversation->messages()
        //         ->where('sender_id', $contact->id)
        //         ->where('receiver_id', $user->id)
        //         ->whereNull('read_at')
        //         ->count();

        //     return $contact;
        // })->filter();

        // Kalakal
        $kalakalMessages = Message::with(['sender', 'receiver', 'conversation'])
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
            })
            ->latest('created_at')
            ->get()
            // Only keep messages whose conversation is 'kalakalkoto'
            ->filter(function ($message) {
                return $message->conversation && $message->conversation->type === 'kalakalkoto';
            })
            // Get only the latest per contact
            ->unique(function ($message) use ($user) {
                return $message->sender_id === $user->id
                    ? $message->receiver_id
                    : $message->sender_id;
            });

        $kalakalUsers = $kalakalMessages->map(function ($message) use ($user) {
            $contact = $message->sender_id === $user->id
                ? $message->receiver
                : $message->sender;

            if ($contact) {
                $contact->last_message = $message->message;
                $contact->last_message_read_at = $message->read_at;
                $contact->last_message_created_at = $message->created_at;
                $contact->last_message_sender_name = $message->sender->name;
                $contact->last_message_sender_id = $message->sender->id;

                $contact->unread_count = Message::where('sender_id', $contact->id)
                    ->where('receiver_id', $user->id)
                    ->whereHas('conversation', fn($q) => $q->where('type', 'kalakalkoto'))
                    ->whereNull('read_at')
                    ->count();
            }

            return $contact;
        })->filter();

        // Adkoto
        $adkotoMessages = Message::with(['sender', 'receiver', 'conversation'])
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
            })
            ->latest('created_at')
            ->get()
            // Only keep messages whose conversation is 'Adkoto'
            ->filter(function ($message) {
                return $message->conversation && $message->conversation->type === 'adkoto';
            })
            // Get only the latest per contact
            ->unique(function ($message) use ($user) {
                return $message->sender_id === $user->id
                    ? $message->receiver_id
                    : $message->sender_id;
            });

        $adkotoUsers = $adkotoMessages->map(function ($message) use ($user) {
            $contact = $message->sender_id === $user->id
                ? $message->receiver
                : $message->sender;

            if ($contact) {
                $contact->last_message = $message->message;
                $contact->last_message_read_at = $message->read_at;
                $contact->last_message_created_at = $message->created_at;
                $contact->last_message_sender_name = $message->sender->name;
                $contact->last_message_sender_id = $message->sender->id;

                $contact->unread_count = Message::where('sender_id', $contact->id)
                    ->where('receiver_id', $user->id)
                    ->whereHas('conversation', fn($q) => $q->where('type', 'adkoto'))
                    ->whereNull('read_at')
                    ->count();
            }

            return $contact;
        })->filter();

        return Inertia::render('Chat/Home', [
            'messageUsers' => UserResource::collection($messageUsers),
            'groupChats' => GroupChatResource::collection($groupChats),
            'kalakalUsers' => UserResource::collection($kalakalUsers),
            'adkotoUsers' => UserResource::collection($adkotoUsers),
        ]);
    }

    public function getLatestMessages(Request $request)
    {
        $user = auth()->user();

        $latestMessages = Message::with(['sender', 'receiver', 'conversation'])
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
            })
            ->latest('created_at')
            ->get()
            // Only keep messages whose conversation is 'tribekoto'
            ->filter(function ($message) {
                return $message->conversation && $message->conversation->type === 'tribekoto';
            })
            // Get only the latest per contact
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
                $contact->last_message_read_at = $message->read_at;
                $contact->last_message_created_at = $message->created_at;
                $contact->last_message_sender_name = $message->sender->name;
                $contact->last_message_sender_id = $message->sender->id;

                $contact->unread_count = Message::where('sender_id', $contact->id)
                    ->where('receiver_id', $user->id)
                    ->whereHas('conversation', fn($q) => $q->where('type', 'tribekoto'))
                    ->whereNull('read_at')
                    ->count();
            }

            return $contact;
        })->filter();


        // $latestMessages = Message::where(function ($query) use ($user) {
        //     $query->where('sender_id', $user->id)
        //         ->orWhere('receiver_id', $user->id);
        // })
        //     ->latest('created_at')
        //     ->get()
        //     ->unique(function ($message) use ($user) {
        //         return $message->sender_id === $user->id
        //             ? $message->receiver_id
        //             : $message->sender_id;
        //     });

        // $messageUsers = $latestMessages->map(function ($message) use ($user) {
        //     $contact = $message->sender_id === $user->id
        //         ? $message->receiver
        //         : $message->sender;

        //     if ($contact) {
        //         $contact->last_message = $message->message;
        //         $contact->last_message_read_at = $message->read_at;
        //         $contact->last_message_created_at = $message->created_at;
        //         $contact->last_message_sender_name = $message->sender->name;
        //         $contact->last_message_sender_id = $message->sender->id;
        //         $contact->unread_count = Message::where('sender_id', $contact->id)
        //             ->where('receiver_id', $user->id)
        //             ->whereNull('read_at')
        //             ->count();
        //     }

        //     return $contact;
        // })->filter();

        // $groupChats = GroupChat::whereHas('participants', function ($query) use ($user) {
        //     $query->where('user_id', $user->id);
        // })->get();

        $groupChats = GroupChat::whereHas('participants', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->with([
                'messages' => function ($query) {
                    $query->latest()->limit(1)->with('sender');
                }
            ])
            ->get()
            ->map(function ($group) use ($user) {
                // Get last message details
                $lastMessage = $group->messages->first();

                // Add group chat metadata
                $group->last_message = $lastMessage ? $lastMessage->message : null;
                // $group->last_message_created_at = $lastMessage ? $lastMessage->created_at : null;
                $group->last_message_created_at = optional($lastMessage)->created_at;
                $group->last_message_sender_name = $lastMessage ? $lastMessage->sender->name : null;
                $group->last_message_sender_id = $lastMessage ? $lastMessage->sender_id : null;
                $group->timestamp = $lastMessage ? $lastMessage->created_at : null;

                // Calculate unread messages for group (example implementation)
                $group->unread_count = Message::where('group_id', $group->id)
                    ->where('created_at', '>', $user->groupReadPivot->last_read_at ?? now()->subYears(10))
                    ->count();

                return $group;
            });

        // Kalakal
        $kalakalMessages = Message::with(['sender', 'receiver', 'conversation'])
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
            })
            ->latest('created_at')
            ->get()
            // Only keep messages whose conversation is 'kalakalkoto'
            ->filter(function ($message) {
                return $message->conversation && $message->conversation->type === 'kalakalkoto';
            })
            // Get only the latest per contact
            ->unique(function ($message) use ($user) {
                return $message->sender_id === $user->id
                    ? $message->receiver_id
                    : $message->sender_id;
            });

        $kalakalUsers = $kalakalMessages->map(function ($message) use ($user) {
            $contact = $message->sender_id === $user->id
                ? $message->receiver
                : $message->sender;

            if ($contact) {
                $contact->last_message = $message->message;
                $contact->last_message_read_at = $message->read_at;
                $contact->last_message_created_at = $message->created_at;
                $contact->last_message_sender_name = $message->sender->name;
                $contact->last_message_sender_id = $message->sender->id;

                $contact->unread_count = Message::where('sender_id', $contact->id)
                    ->where('receiver_id', $user->id)
                    ->whereHas('conversation', fn($q) => $q->where('type', 'kalakalkoto'))
                    ->whereNull('read_at')
                    ->count();
            }

            return $contact;
        })->filter();

        // $kalakalUsers = Conversation::where('user_id2', $user->id)
        //     ->where('type', 'kalakalkoto')
        //     ->with('user1')
        //     ->get();

        // Adkoto
        $adkotoMessages = Message::with(['sender', 'receiver', 'conversation'])
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
            })
            ->latest('created_at')
            ->get()
            // Only keep messages whose conversation is 'Adkoto'
            ->filter(function ($message) {
                return $message->conversation && $message->conversation->type === 'adkoto';
            })
            // Get only the latest per contact
            ->unique(function ($message) use ($user) {
                return $message->sender_id === $user->id
                    ? $message->receiver_id
                    : $message->sender_id;
            });

        $adkotoUsers = $adkotoMessages->map(function ($message) use ($user) {
            $contact = $message->sender_id === $user->id
                ? $message->receiver
                : $message->sender;

            if ($contact) {
                $contact->last_message = $message->message;
                $contact->last_message_read_at = $message->read_at;
                $contact->last_message_created_at = $message->created_at;
                $contact->last_message_sender_name = $message->sender->name;
                $contact->last_message_sender_id = $message->sender->id;

                $contact->unread_count = Message::where('sender_id', $contact->id)
                    ->where('receiver_id', $user->id)
                    ->whereHas('conversation', fn($q) => $q->where('type', 'adkoto'))
                    ->whereNull('read_at')
                    ->count();
            }

            return $contact;
        })->filter();

        return response()->json([
            'messageUsers' => UserResource::collection($messageUsers),
            'groupChats' => GroupChatResource::collection($groupChats),
            'kalakalUsers' => UserResource::collection($kalakalUsers),
            'adkotoUsers' => UserResource::collection($adkotoUsers),
        ]);
    }

    public function getUnreadCount(Request $request)
    {
        $user = auth()->user();

        $unreadCount = Message::where('receiver_id', $user->id)
            ->whereNull('read_at')
            ->distinct('sender_id')
            ->count('sender_id');

        return response()->json([
            'unreadCount' => $unreadCount,
        ]);
    }


    public function markAsRead($conversationId)
    {
        $user = auth()->user();

        Message::where('conversation_id', $conversationId)
            ->where('receiver_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['status' => 'success']);
    }

    public function searchFollowings(Request $request)
    {
        $user = auth()->user();
        $query = $request->input('query', '');

        $followings = $user->followings()
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('name', 'like', '%' . $query . '%')
                    ->orWhere('surname', 'like', '%' . $query . '%')
                    ->orWhere('username', 'like', '%' . $query . '%');
            })
            ->distinct()
            ->get();

        return response()->json([
            'followings' => UserResource::collection($followings),
        ]);
    }


    public function getConversation(User $user)
    {
        $appId = env('AGORA_APP_ID');

        $isBlockedByAuthUser = Block::query()
            ->where('user_id', auth()->id()) // Authenticated user blocked the other user
            ->where('blocked_user_id', $user->id)
            ->exists();

        $isBlockedByOtherUser = Block::query()
            ->where('user_id', $user->id) // Other user blocked the authenticated user
            ->where('blocked_user_id', auth()->id())
            ->exists();

        $conversation = Conversation::where(function ($query) use ($user) {
            $query->where('user_id1', auth()->id())
                ->where('user_id2', $user->id)
                ->where('type', 'tribekoto');
        })->orWhere(function ($query) use ($user) {
            $query->where('user_id1', $user->id)
                ->where('user_id2', auth()->id())
                ->where('type', 'tribekoto');
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
            'user' => new UserResource($user),
            'isBlockedByAuthUser' => $isBlockedByAuthUser,
            'isBlockedByOtherUser' => $isBlockedByOtherUser,
            'appId' => $appId,
        ]);
    }

    public function callPage(Request $request)
    {
        $appID = env('AGORA_APP_ID');

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
                $contact->last_message_read_at = $message->read_at;
                $contact->last_message_sender_name = $message->sender->name;
                $contact->last_message_sender_id = $message->sender->id;
                $contact->unread_count = Message::where('sender_id', $contact->id)
                    ->where('receiver_id', $user->id)
                    ->whereNull('read_at')
                    ->count();
            }

            return $contact;
        })->filter();

        // $user = User::findOrFail($userId);
        return Inertia::render('Chat/Call', [
            // 'user' => new UserResource($user),
            'users' => UserResource::collection($messageUsers),
            'appID' => $appID,
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
        $request->validate([
            'receiver_id' => 'required|integer|exists:users,id',
            'conversation_id' => 'required|integer|exists:conversations,id',
            'message' => 'nullable|string',
            // 'files.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi,webm,pdf,doc,docx,zip,cdr|max:20480',
            'files.*' => [
                'nullable',
                'file',
                'max:20480',
                function ($attribute, $value, $fail) {
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov', 'avi', 'webm', 'pdf', 'doc', 'docx', 'zip', 'cdr'];
                    if (!in_array($value->getClientOriginalExtension(), $allowedExtensions)) {
                        $fail("The $attribute must be a file of type: " . implode(', ', $allowedExtensions));
                    }
                },
            ]
        ]);

        if (empty($request->message) && !$request->hasFile('files')) {
            return response()->json(['error' => 'Message or files are required'], 400);
        }

        try {
            // Create the message
            $message = Message::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $request->receiver_id,
                'conversation_id' => $request->conversation_id,
                'message' => $request->message,
            ]);

            // Update the conversation's last message
            Conversation::where('id', $request->conversation_id)->update([
                'last_message_id' => $message->id,
            ]);

            // Handle file uploads if there are any
            if ($request->hasFile('files')) {
                $attachments = [];
                foreach ($request->file('files') as $file) {
                    // Store the file and get its path
                    $path = $file->store('message_attachments/' . $message->id, 'public');

                    // Prepare the attachment data for batch insert
                    $attachments[] = [
                        'message_id' => $message->id,
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'mime' => $file->getMimeType(),
                        'size' => $file->getSize(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Batch insert the attachments
                MessageAttachment::insert($attachments);
            }

            // Broadcast the message to a channel
            Log::info('Broadcasting message: ', ['message' => $message]);
            broadcast(new MessageSent($message))->toOthers();
            broadcast(new RefreshChatHome($message))->toOthers();

            return response()->json($message);
        } catch (\Exception $e) {
            Log::error('Failed to send message: ', ['error' => $e->getMessage()]);
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
        $request->validate([
            'message' => 'nullable|string',
            // 'files.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:20480',
            'files.*' => [
                'nullable',
                'file',
                'max:20480',
                function ($attribute, $value, $fail) {
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov', 'avi', 'webm', 'pdf', 'doc', 'docx', 'zip', 'cdr'];
                    if (!in_array($value->getClientOriginalExtension(), $allowedExtensions)) {
                        $fail("The $attribute must be a file of type: " . implode(', ', $allowedExtensions));
                    }
                },
            ]
        ]);

        if (empty($request->message) && !$request->hasFile('files')) {
            return response()->json(['error' => 'Message or files are required'], 400);
        }

        try {
            // Create the message
            $message = Message::create([
                'sender_id' => auth()->id(),
                'group_id' => $group->id,
                'message' => $request->message,
            ]);

            // Update last message in the group
            $group->update(['last_message_id' => $message->id]);

            // Handle file uploads if there are any
            if ($request->hasFile('files')) {
                $attachments = [];
                foreach ($request->file('files') as $file) {
                    // Store the file and get its path
                    $path = $file->store('message_attachments/' . $message->id, 'public');

                    // Prepare the attachment data for batch insert
                    $attachments[] = [
                        'message_id' => $message->id,
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'mime' => $file->getMimeType(),
                        'size' => $file->getSize(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Batch insert the attachments
                MessageAttachment::insert($attachments);
            }

            // Broadcast the message to the group
            Log::info('Broadcasting message to group chat: ', ['message' => $message]);
            broadcast(new GroupChatMessageSent($message))->toOthers();

            return response()->json($message);
        } catch (\Exception $e) {
            Log::error('Failed to send message to group: ', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to send message'], 500);
        }
    }
}
