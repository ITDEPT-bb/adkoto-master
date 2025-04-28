<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class GroupChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $participants = $this->participants->filter(function ($participant) {
            return !$participant->deleted_at;
        });

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'photo' => $this->photo ? Storage::url($this->photo) : '/img/no_image.png',
            'owner' => new UserResource($this->owner),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            // 'participants' => UserResource::collection($this->participants),
            'participants' => UserResource::collection($participants),
            // 'last_message' => $this->when(isset($this->last_message), $this->last_message),
            // 'last_message_sender_name' => $this->when(isset($this->last_message_sender_name), $this->last_message_sender_name),
            'last_message' => $this->last_message,
            'last_message_created_at' => $this->last_message_created_at,
            'last_message_sender_name' => $this->last_message_sender_name,
            'last_message_sender_id' => $this->last_message_sender_id,
            // 'unread_count' => $this->unread_count,
        ];
    }
}
