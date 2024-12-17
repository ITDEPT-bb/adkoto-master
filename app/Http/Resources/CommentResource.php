<?php

namespace App\Http\Resources;

use App\Traits\HandlesSoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CommentResource extends JsonResource
{
    use HandlesSoftDeletes;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isDeactivated = $this->resource ? $this->isDeactivated($this->resource) : false;

        return [
            'id' => $this->id,
            'comment' => $this->comment,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'num_of_reactions' => $this->reactions_count,
            'num_of_comments' => $this->numOfComments,
            'current_user_has_reaction' => $this->reactions->count() > 0,
            'comments' => $this->childComments,
            'user' => [
                "id" => $this->user ? $this->user->id : null,
                "name" => $this->user ? $this->user->name : null,
                "surname" => $this->user ? $this->user->surname : null,
                "username" => $this->user ? $this->user->username : null,
                "avatar_url" => $this->user && $this->user->avatar_path
                    ? Storage::url($this->user->avatar_path)
                    : '/img/default_avatar.png',
            ],

            'is_deactivated' => $isDeactivated,
            'deactivation_message' => $isDeactivated ? 'This user has been deactivated.' : null,
        ];
    }
}
