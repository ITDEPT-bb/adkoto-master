<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KalakalContactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $other = $this->user1;

        return [
            'id' => $other->id,
            'name' => $other->name,
            'surname' => $other->surname,
            'avatar_url' => $other->avatar_url,
            'last_message' => null, 
            'last_message_read_at' => null,
            'last_message_sender_name' => null,
            'last_message_sender_id' => null,
            'unread_count' => 0, 
        ];
    }
}
