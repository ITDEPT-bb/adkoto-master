<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\PostAttachmentResource;
use App\Http\Resources\PostResource;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

use App\Http\Resources\UserResource;
use App\Models\Follower;
use App\Models\Post;
use App\Models\PostAttachment;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */

    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Update', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    public function index(Request $request, User $user)
    {
        $isCurrentUserFollower = false;
        if (!Auth::guest()) {
            $isCurrentUserFollower = Follower::where('user_id', $user->id)->where('follower_id', Auth::id())->exists();
        }
        $followerCount = Follower::where('user_id', $user->id)->count();

        $posts = Post::postsForTimeline(Auth::id(), false)
            ->leftJoin('users AS u', 'u.pinned_post_id', 'posts.id')
            ->where('user_id', $user->id)
            ->whereNull('group_id')
            ->orderBy('u.pinned_post_id', 'desc')
            ->orderBy('posts.created_at', 'desc')
            ->paginate(10);

        $posts = PostResource::collection($posts);
        if ($request->wantsJson()) {
            return $posts;
        }

        $followers = $user->followers;

        $followings = $user->followings;

        $photos = PostAttachment::query()
            ->where('mime', 'like', 'image/%')
            ->where('created_by', $user->id)
            ->latest()
            ->get();

        return Inertia::render('Profile/View', [
            'mustVerifyEmail' => $user instanceof MustVerifyEmail,
            'status' => session('status'),
            'success' => session('success'),
            'isCurrentUserFollower' => $isCurrentUserFollower,
            'followerCount' => $followerCount,
            'user' => new UserResource($user),
            'posts' => $posts,
            'followers' => UserResource::collection($followers),
            'followings' => UserResource::collection($followings),
            'photos' => PostAttachmentResource::collection($photos)
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        // return Redirect::route('profile.edit');
        return to_route('profile', $request->user())->with('success', 'Your profile details were updated.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function validatePassword(Request $request)
    {
        $request->validate(['password' => 'required']);

        if (!Hash::check($request->password, $request->user()->password)) {
            return response()->json(['error' => 'The password is incorrect.'], 422);
        }

        return response()->json(['success' => true]);
    }

    public function deactivate(Request $request)
    {
        $user = $request->user();
        try {
            $user->delete();
            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deactivating your account.'], 500);
        }
    }

    public function updateImage(Request $request)
    {
        $data = $request->validate([
            'cover' => ['nullable', 'image'],
            'avatar' => ['nullable', 'image']
        ]);

        $user = $request->user();

        $avatar = $data['avatar'] ?? null;
        /** @var \Illuminate\Http\UploadedFile $cover */
        $cover = $data['cover'] ?? null;

        $success = '';
        if ($cover) {
            // $path = $cover->store('avatars/' . $user->id, 'public');
            if ($user->cover_path) {
                Storage::disk('public')->delete($user->cover_path);
            }
            $path = $cover->store('user-' . $user->id, 'public');
            $user->update(['cover_path' => $path]);
            $success = 'Your cover image was updated';
        }

        // session('success', 'Cover image has been updated');
        if ($avatar) {
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $path = $avatar->store('user-' . $user->id, 'public');
            $user->update(['avatar_path' => $path]);
            $success = 'Your avatar image was updated';
        }

        return back()->with('success', $success);
    }

    // public function checkProfile()
    // {
    //     $user = Auth::user();

    //     $isProfileComplete = $user->surname && $user->username && $user->birthday && $user->gender;

    //     return response()->json(['isProfileComplete' => $isProfileComplete]);
    // }

    public function fetchProfileNotif($userId)
    {
        $user = User::findOrFail($userId);

        return response()->json(new UserResource($user));
    }

    public function coverPage(Request $request, User $user)
    {
        return Inertia::render('Profile/EditCover', [
            'user' => new UserResource($user),
        ]);
    }

    public function updateCover(Request $request)
    {
        $data = $request->validate([
            'cover' => ['nullable', 'image', 'max:2048'],
            'share_cover' => ['nullable', 'boolean'],
        ]);

        $user = $request->user();

        /** @var \Illuminate\Http\UploadedFile $cover */
        $cover = $data['cover'] ?? null;

        if ($cover) {
            // Delete old cover if it exists
            if ($user->cover_path) {
                Storage::disk('public')->delete($user->cover_path);
            }

            // Store the new cover image
            $path = $cover->store('user-' . $user->id . '/cover', 'public');
            $user->update(['cover_path' => $path]);

            // Share as a post if requested
            if (!empty($data['share_cover'])) {
                DB::beginTransaction();
                try {
                    // Create the post
                    $post = Post::create([
                        'body' => 'Check out my new Cover Photo!',
                        'user_id' => $user->id,
                    ]);

                    $post_path = $cover->store('attachments/' . $post->id, 'public');
                    // Create post attachment
                    PostAttachment::create([
                        'post_id' => $post->id,
                        'name' => $cover->getClientOriginalName(),
                        'path' => $post_path,
                        'mime' => $cover->getMimeType(),
                        'size' => $cover->getSize(),
                        'created_by' => $user->id,
                    ]);

                    DB::commit();
                } catch (\Exception $e) {
                    // Rollback changes if any error occurs
                    Storage::disk('public')->delete($path);
                    DB::rollBack();
                    throw $e;
                }
            }

            return response()->json([
                'message' => 'Your cover was updated successfully!',
                'redirect' => route('profile', $user->username),
            ], 200);
        }

        return response()->json(['message' => 'No image provided'], 400);
    }

    public function avatarPage(Request $request, User $user)
    {
        return Inertia::render('Profile/EditAvatar', [
            'user' => new UserResource($user),
        ]);
    }

    public function updateAvatar(Request $request)
    {
        $data = $request->validate([
            'avatar' => ['nullable', 'image', 'max:2048'],
            'share_avatar' => ['nullable', 'boolean'],
        ]);

        $user = $request->user();

        /** @var \Illuminate\Http\UploadedFile $avatar */
        $avatar = $data['avatar'] ?? null;

        if ($avatar) {
            // Delete old avatar if exists
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }

            // Store new avatar
            $path = $avatar->store('user-' . $user->id, 'public');
            $user->update(['avatar_path' => $path]);

            // Share as a post if requested
            if (!empty($data['share_avatar'])) {
                DB::beginTransaction();
                try {
                    // Create the post
                    $post = Post::create([
                        'body' => 'Check out my new Profile Picture!',
                        'user_id' => $user->id,
                    ]);

                    $post_path = $avatar->store('attachments/' . $post->id, 'public');
                    // Create post attachment
                    PostAttachment::create([
                        'post_id' => $post->id,
                        'name' => $avatar->getClientOriginalName(),
                        'path' => $post_path,
                        'mime' => $avatar->getMimeType(),
                        'size' => $avatar->getSize(),
                        'created_by' => $user->id,
                    ]);

                    DB::commit();
                } catch (\Exception $e) {
                    // Rollback changes if any error occurs
                    Storage::disk('public')->delete($path);
                    DB::rollBack();
                    throw $e;
                }
            }

            return response()->json([
                'message' => 'Your avatar was updated successfully!',
                'redirect' => route('profile', $user->username),
            ], 200);
        }

        return response()->json(['error' => 'No image provided'], 400);
    }
}
