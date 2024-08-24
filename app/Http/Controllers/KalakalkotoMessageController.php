<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\KalakalkotoConversation;
use App\Models\KalakalkotoItem;
use App\Models\KalakalkotoMessage;
use App\Models\KalakalkotoMessageAttachment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class KalakalkotoMessageController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Fetch conversations where the user is the buyer or seller
        $conversations = KalakalkotoConversation::where(function ($query) use ($user) {
            $query->where('buyer_id', $user->id)
                ->orWhere('seller_id', $user->id);
        })->with('item')->get();

        // Map participants based on whether the user is the buyer or seller
        $participants = $conversations->map(function ($conversation) use ($user) {
            $participant = $conversation->buyer_id === $user->id
                ? User::find($conversation->seller_id)
                : User::find($conversation->buyer_id);

            return [
                'participant' => $participant,
                'item_id' => $conversation->kalakal_id,
            ];
        })->unique('participant.id');

        $formattedParticipants = $participants->map(function ($entry) {
            return [
                'participant' => new UserResource($entry['participant']),
                'item_id' => $entry['item_id'],
            ];
        });

        // Render the view with the formatted participants
        return Inertia::render('Kalakalkoto/ChatHome', [
            'participants' => $formattedParticipants,
        ]);
    }



    // public function showList(Request $request, $kalakalId)
    // {
    //     $user = auth()->user();

    //     $conversations = KalakalkotoConversation::where('kalakal_id', $kalakalId)
    //         ->where(function ($query) use ($user) {
    //             $query->where('user_id1', $user->id)
    //                 ->orWhere('user_id2', $user->id);
    //         })
    //         ->get();

    //     $participants = $conversations->flatMap(function ($conversation) use ($user) {
    //         return $conversation->user_id1 === $user->id
    //             ? User::where('id', $conversation->user_id2)->get()
    //             : User::where('id', $conversation->user_id1)->get();
    //     })->unique('id');

    //     return Inertia::render('Kalakalkoto/ChatHome', [
    //         'participants' => User::collection($participants),
    //         'conversations' => $conversations,
    //         'item' => KalakalkotoItem::find($kalakalId),
    //     ]);
    // }

    public function getConversation(User $user, $kalakalId)
    {
        // Retrieve the item based on ID
        $item = KalakalkotoItem::findOrFail($kalakalId);

        $conversation = KalakalkotoConversation::where('kalakal_id', $item->id)
            ->where(function ($query) use ($user) {
                $query->where('buyer_id', auth()->id())
                    ->where('seller_id', $user->id);
            })->orWhere(function ($query) use ($user) {
                $query->where('buyer_id', $user->id)
                    ->where('seller_id', auth()->id());
            })->with(['messages.attachments', 'item'])
            ->first();

        // If no conversation exists, create a new one
        if (!$conversation) {
            $conversation = KalakalkotoConversation::create([
                'kalakal_id' => $item->id,
                'buyer_id' => auth()->id(),
                'seller_id' => $user->id,
            ]);
        }

        $messages = $conversation->messages;

        // Include item details in the response
        return Inertia::render('Kalakalkoto/ChatWindow', [
            'conversation' => $conversation,
            'messages' => $messages,
            'user' => new UserResource($user),
            'item' => $item,
        ]);
    }

    public function fetchMessages($conversationId)
    {
        $conversation = KalakalkotoConversation::with('messages.attachments')
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
            $message = KalakalkotoMessage::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $request->receiver_id,
                'conversation_id' => $request->conversation_id,
                'message' => $request->message,
            ]);

            // Handle file uploads if there are any
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    // Store the file and get its path
                    $path = $file->store('kalakal_message_attachments/' . $message->id, 'public');

                    // Create an entry for each uploaded file
                    KalakalkotoMessageAttachment::create([
                        'message_id' => $message->id,
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'mime' => $file->getMimeType(),
                        'size' => $file->getSize(),
                    ]);
                }
            }

            DB::commit();

            return response()->json($message);
        } catch (\Exception $e) {
            // Rollback the transaction on failure
            DB::rollBack();
            return response()->json(['error' => 'Failed to send message'], 500);
        }
    }
}
