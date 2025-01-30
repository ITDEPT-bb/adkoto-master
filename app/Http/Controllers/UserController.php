<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Follower;
use App\Models\User;
use App\Notifications\FollowRequestAccepted;
use App\Notifications\FollowUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // public function follow(Request $request, User $user)
    // {
    //     $data = $request->validate([
    //         'follow' => ['boolean']
    //     ]);
    //     if ($data['follow']) {
    //         if ($user->is_private) {
    //             $status = 'pending';
    //         } else {
    //             $status = 'accepted';
    //         }

    //         $message = 'You followed user "' . $user->name . '"';
    //         Follower::create([
    //             'user_id' => $user->id,
    //             'follower_id' => Auth::id(),
    //             'status' => $status
    //         ]);
    //     } else {
    //         $message = 'You unfollowed user "' . $user->name . '"';
    //         Follower::query()
    //             ->where('user_id', $user->id)
    //             ->where('follower_id', Auth::id())
    //             ->delete();
    //     }

    //     $user->notify(new FollowUser(Auth::getUser(), $data['follow']));

    //     return back()->with('success', $message);
    // }

    public function follow(Request $request, User $user)
    {
        $data = $request->validate([
            'follow' => ['boolean'],
        ]);

        if ($data['follow']) {
            // Check if the user is private to determine the follow status
            $status = $user->is_private ? 'pending' : 'accepted';

            // Check if the follow relationship already exists
            $existingFollower = Follower::query()
                ->where('user_id', $user->id)
                ->where('follower_id', Auth::id())
                ->first();

            if ($existingFollower) {
                // Update the status only if it needs to be changed
                if ($existingFollower->status !== $status) {
                    $existingFollower->update(['status' => $status]);
                }
            } else {
                // Create a new follow relationship
                Follower::create([
                    'user_id' => $user->id,
                    'follower_id' => Auth::id(),
                    'status' => $status,
                ]);
            }

            $message = $user->is_private
                ? 'Follow request sent to "' . $user->name . '"'
                : 'You followed user "' . $user->name . '"';
        } else {
            // Unfollow logic or cancel follow request
            $message = $user->is_private
                ? 'You canceled the follow request for "' . $user->name . '"'
                : 'You unfollowed user "' . $user->name . '"';

            Follower::query()
                ->where('user_id', $user->id)
                ->where('follower_id', Auth::id())
                ->delete();
        }

        // Notify the user only for new follow actions (not for unfollow or cancel requests)
        if ($data['follow']) {
            // $user->notify(new FollowUser(Auth::getUser(), $data['follow']));
            $user->notify(new FollowUser(Auth::getUser(), $data['follow'], $status));
        }

        return back()->with('success', $message);
    }

    public function accept($followerId)
    {
        $followRequest = Follower::where('user_id', Auth::id())
            ->where('follower_id', $followerId)
            ->where('status', 'pending')
            ->firstOrFail();

        $followRequest->status = 'accepted';
        $followRequest->save();

        // Fetch the follower (the user whose request is being accepted)
        $followerUser = $followRequest->follower;

        // Send a notification to the follower user
        $currentUser = Auth::user(); // The user who is accepting the request
        $followerUser->notify(new FollowRequestAccepted($followerUser, $currentUser));

        $message = 'Follow request accepted';
        return back()->with('success', $message);
    }

    public function reject($followerId)
    {
        $followRequest = Follower::where('user_id', Auth::id())
            ->where('follower_id', $followerId)
            ->where('status', 'pending')
            ->firstOrFail();

        // $followRequest->status = 'rejected';
        // $followRequest->save();

        $followRequest->delete();

        $message = 'Follow request rejected';
        return back()->with('success', $message);
        // return response()->json(['message' => 'Follow request rejected.']);
    }

    public function block(Request $request, User $user)
    {
        $authUserId = auth()->id();

        if ($authUserId === $user->id) {
            $message = 'You cannot block yourself.';
            return back()->with('error', $message);
        }

        $alreadyBlocked = Block::where('user_id', $authUserId)
            ->where('blocked_user_id', $user->id)
            ->exists();

        if ($alreadyBlocked) {
            $message = 'User already blocked.';
            return back()->with('error', $message);
        }

        Block::create([
            'user_id' => $authUserId,
            'blocked_user_id' => $user->id,
        ]);

        $message = 'User successfully blocked.';

        return back()->with('success', $message);
    }

    public function unblock(Request $request, User $user)
    {
        $authUserId = auth()->id();

        $block = Block::where('user_id', $authUserId)
            ->where('blocked_user_id', $user->id)
            ->first();

        if (!$block) {
            return back()->with('error', 'User not found in your blocked list.');
        }

        $block->delete();

        // Check and delete the follower record if it exists
        $followAuth = Follower::where('user_id', $authUserId)
            ->where('follower_id', $user->id)
            ->first();
        if ($followAuth) {
            $followAuth->delete();
        }

        $followUser = Follower::where('user_id', $user->id)
            ->where('follower_id', $authUserId)
            ->first();
        if ($followUser) {
            $followUser->delete();
        }

        return back()->with('success', 'User successfully unblocked.');
    }
}
