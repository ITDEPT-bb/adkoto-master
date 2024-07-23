<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    public function index(Request $request)
    {
        $user = auth()->user();

        $followings = $user->followings()->get();

        $conversations = Conversation::where(function ($query) use ($user) {
            $query->where('user_id1', $user->id)
                ->orWhere('user_id2', $user->id);
        })->get();

        $participants = $conversations->map(function ($conversation) use ($user) {
            return $conversation->user_id1 === $user->id
                ? User::find($conversation->user_id2)
                : User::find($conversation->user_id1);
        })->unique('id');

        $filteredParticipants = $participants->reject(function ($participant) use ($followings) {
            return $followings->contains('id', $participant->id);
        });

        return Inertia::render('Chat/Home', [
            'followings' => UserResource::collection($followings),
            'participants' => UserResource::collection($filteredParticipants),
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
        })->with('messages')->first();

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
        $conversation = Conversation::with('messages')
            ->where('id', $conversationId)
            ->first();

        if (!$conversation) {
            return response()->json(['message' => 'Conversation not found'], 404);
        }

        $messages = $conversation->messages;

        return response()->json($messages);
    }

    /**
     * Send a message to a conversation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required',
            'conversation_id' => 'required',
            'message' => 'required',
        ]);

        // Start a database transaction
        DB::beginTransaction();

        try {
            $message = Message::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $request->receiver_id,
                'conversation_id' => $request->conversation_id,
                'message' => $request->message,
            ]);

            // Commit the transaction
            DB::commit();

            // Optionally, broadcast the message to a channel
            // broadcast(new MessageSent($message));

            return response()->json($message);
        } catch (\Exception $e) {
            // Rollback the transaction on failure
            DB::rollBack();
            return response()->json(['error' => 'Failed to send message'], 500);
        }
    }
}
