<?php

namespace App\Http\Requests;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;

// class UpdatePostRequest extends FormRequest
class UpdatePostRequest extends StorePostRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $post = $this->route('post');

        return $post->user_id == Auth::id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // return [
        //     'body' => ['nullable', 'string'],
        //     'user_id' => ['numeric']
        // ];
        return array_merge(parent::rules(), [
            'deleted_file_ids' => 'array',
            'deleted_file_ids.*' => 'numeric',
        ]);
    }
}
