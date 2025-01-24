<?php

namespace App\Http\Resources;

use App\Traits\HandlesSoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{

    use HandlesSoftDeletes;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $isDeactivated = $this->resource ? $this->isDeactivated($this->resource) : false;

        return [
            "id" => $this->resource ? $this->id : null,
            "name" => $this->resource ? $this->name : null,
            "surname" => $this->resource ? $this->surname : null,
            "email" => $this->resource ? $this->email : null,
            "phone" => $this->resource ? $this->phone : null,
            "birthday" => $this->resource ? $this->birthday : null,
            "gender" => $this->resource ? $this->gender : null,
            "email_verified_at" => $this->resource ? $this->email_verified_at : null,
            "is_admin" => $this->resource ? $this->is_admin : null,
            "is_filament_admin" => $this->resource ? $this->is_filament_admin : null,
            "created_at" => $this->resource ? $this->created_at : null,
            "updated_at" => $this->resource ? $this->updated_at : null,
            "username" => $this->resource ? $this->username : null,
            'pinned_post_id' => $this->resource ? $this->pinned_post_id : null,
            // "cover_url" => $this->cover_path ? Storage::url($this->cover_path) : null,
            "cover_url" => $this->cover_path ? Storage::url($this->cover_path) : '/img/default_cover.jpg',
            "avatar_url" => $this->avatar_path ? Storage::url($this->avatar_path) : '/img/default_avatar.png',
            // "is_private" => $this->resource ? $this->is_private : null,
            'last_message' => $this->when(isset($this->last_message), $this->last_message),
            'last_message_sender_name' => $this->when(isset($this->last_message_sender_name), $this->last_message_sender_name),
            'last_message_sender_id' => $this->when(isset($this->last_message_sender_id), $this->last_message_sender_id),
            'last_message_read_at' => $this->when(isset($this->last_message_read_at), $this->last_message_read_at),
            'unread_count' => $this->when(isset($this->unread_count), $this->unread_count),
            'is_deactivated' => $isDeactivated,
            'deactivation_message' => $isDeactivated ? 'This user has been deactivated.' : null,
        ];
    }
}
