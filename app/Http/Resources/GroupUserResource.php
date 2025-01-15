<?php

namespace App\Http\Resources;

use App\Traits\HandlesSoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class GroupUserResource extends JsonResource
{
    use HandlesSoftDeletes;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $this->user;
        $isDeactivated = $user ? $this->isDeactivated($user) : false;

        return [
            "id" => $this->id,
            "name" => $this->name,
            'role' => $this->role,
            'status' => $this->status,
            'group_id' => $this->group_id,
            "username" => $this->username,
            "avatar_url" => $this->avatar_path ? Storage::url($this->avatar_path) : '/img/default_avatar.png',
            'user' => [
                'id' => $user ? $user->id : null,
                'name' => $user ? $user->name : null,
                'is_deactivated' => $isDeactivated,
            ],
        ];
    }
}
