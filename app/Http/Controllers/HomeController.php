<?php

namespace App\Http\Controllers;

use App\Http\Enums\GroupUserStatus;
use App\Http\Resources\GroupResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $user = $request->user();

        // $posts = Post::postsForTimeline($userId)
        //     ->select('posts.*')
        //     ->leftJoin('followers AS f', function ($join) use ($userId) {
        //         $join->on('posts.user_id', '=', 'f.user_id')
        //             ->where('f.follower_id', '=', $userId);
        //     })
        //     ->leftJoin('group_users AS gu', function ($join) use ($userId) {
        //         $join->on('gu.group_id', '=', 'posts.group_id')
        //             ->where('gu.user_id', '=', $userId)
        //             ->where('gu.status', GroupUserStatus::APPROVED->value);
        //     })
        //     ->join('users', 'users.id', '=', 'posts.user_id')
        //     ->whereNull('users.deleted_at')
        //     ->where(function ($query) use ($userId) {
        /** @var \Illuminate\Database\Query\Builder $query */
        //     $query->whereNotNull('f.follower_id')
        //         ->orWhereNotNull('gu.group_id')
        //         ->orWhere('posts.user_id', $userId);
        // })
        // ->latest()
        // ->paginate(10);

        $posts = Post::postsForTimeline($userId)
            ->select('posts.*')
            ->leftJoin('followers AS f', function ($join) use ($userId) {
                $join->on('posts.user_id', '=', 'f.user_id')
                    ->where('f.follower_id', '=', $userId)
                    ->where('f.status', '=', 'accepted');
            })
            ->leftJoin('blocks AS b', function ($join) use ($userId) {
                $join->on('posts.user_id', '=', 'b.user_id')
                    ->orOn('posts.user_id', '=', 'b.blocked_user_id')
                    ->where(function ($query) use ($userId) {
                        $query->where('b.user_id', '=', $userId)
                            ->orWhere('b.blocked_user_id', '=', $userId);
                    });
            })
            ->whereNull('b.id')
            ->leftJoin('group_users AS gu', function ($join) use ($userId) {
                $join->on('gu.group_id', '=', 'posts.group_id')
                    ->where('gu.user_id', '=', $userId)
                    ->where('gu.status', GroupUserStatus::APPROVED->value);
            })
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->whereNull('users.deleted_at')
            ->where(function ($query) use ($userId) {
                /** @var \Illuminate\Database\Query\Builder $query */
                $query->whereNotNull('f.follower_id')
                    ->orWhereNotNull('gu.group_id')
                    ->orWhere('posts.user_id', $userId);
            })
            ->where(function ($query) use ($userId) {
                /** @var \Illuminate\Database\Query\Builder $query */
                $query->whereNull('posts.group_id') // Include posts not associated with any group
                    ->orWhereExists(function ($subquery) use ($userId) {
                        $subquery->select('id')
                            ->from('group_users')
                            ->whereColumn('group_users.group_id', 'posts.group_id')
                            ->where('group_users.user_id', '=', $userId)
                            ->where('group_users.status', GroupUserStatus::APPROVED->value);
                    });
            })
            ->with(['sharedPost.attachments', 'sharedPost.reactions', 'sharedPost.user'])
            ->withCount('reactions')
            ->withCount('shares')
            ->latest()
            ->paginate(10);

        $posts = PostResource::collection($posts);
        if ($request->wantsJson()) {
            return $posts;
        }

        // $posts = Post::postsForTimeline($userId, false) // disable ->latest() in postsForTimeline
        //     ->select('posts.*')
        //     ->selectRaw(
        //         "
        //             (
        //                 (LOG(LEAST((select count(*) from reactions
        //                     where reactions.object_id = posts.id
        //                     and reactions.object_type = 'App\\\\Models\\\\Post') + 1, 100)) * 5) +  -- reactions
        //                 (LOG(LEAST((select count(*) from posts as sp
        //                     where sp.shared_post_id = posts.id
        //                     and sp.deleted_at is null) + 1, 100)) * 3) +                           -- shares
        //                 (LOG(LEAST((select count(*) from comments
        //                     where comments.post_id = posts.id) + 1, 100)) * 4) +                     -- comments
        //                 (CASE WHEN posts.user_id = ? THEN 0.2 ELSE 0 END) +                          -- small boost for own posts
        //                 (CASE WHEN f.follower_id IS NOT NULL THEN 2 ELSE 0 END) +                    -- boost for followed users
        //                 (CASE WHEN gu.group_id IS NOT NULL THEN 2 ELSE 0 END)                        -- boost for same group
        //             ) / POWER(TIMESTAMPDIFF(SECOND, posts.created_at, NOW()) / 3600 + 2, 1.5)        -- time decay
        //             AS score
        //         ",
        //         [$userId],
        //     )
        //     ->leftJoin('followers AS f', function ($join) use ($userId) {
        //         $join->on('posts.user_id', '=', 'f.user_id')->where('f.follower_id', '=', $userId)->where('f.status', '=', 'accepted');
        //     })
        //     ->leftJoin('blocks AS b', function ($join) use ($userId) {
        //         $join
        //             ->on('posts.user_id', '=', 'b.user_id')
        //             ->orOn('posts.user_id', '=', 'b.blocked_user_id')
        //             ->where(function ($query) use ($userId) {
        //                 $query->where('b.user_id', '=', $userId)->orWhere('b.blocked_user_id', '=', $userId);
        //             });
        //     })
        //     ->whereNull('b.id') // hide blocked users
        //     ->leftJoin('group_users AS gu', function ($join) use ($userId) {
        //         $join->on('gu.group_id', '=', 'posts.group_id')->where('gu.user_id', '=', $userId)->where('gu.status', GroupUserStatus::APPROVED->value);
        //     })
        //     ->join('users', 'users.id', '=', 'posts.user_id')
        //     ->whereNull('users.deleted_at') // hide deleted users
        //     ->whereNull('posts.deleted_at') // hide deleted posts
        //     ->where(function ($query) use ($userId) {
        //         // allow posts that are public or user has access to group
        //         $query->whereNull('posts.group_id')->orWhereExists(function ($subquery) use ($userId) {
        //             $subquery->select('id')->from('group_users')->whereColumn('group_users.group_id', 'posts.group_id')->where('group_users.user_id', '=', $userId)->where('group_users.status', GroupUserStatus::APPROVED->value);
        //         });
        //     })
        //     ->with(['sharedPost.attachments', 'sharedPost.reactions', 'sharedPost.user'])
        //     ->withCount('reactions')
        //     ->withCount('shares')
        //     ->orderByDesc('score') // order by algo score instead of latest
        //     ->paginate(10);

        // $posts = PostResource::collection($posts);

        // if ($request->wantsJson()) {
        //     return $posts;
        // }

        $groups = Group::query()
            ->with('currentUserGroup')
            ->select(['groups.*'])
            ->join('group_users AS gu', 'gu.group_id', 'groups.id')
            ->where('gu.user_id', Auth::id())
            ->orderBy('gu.role')
            ->orderBy('name', 'desc')
            ->get();

        // Get suggested people who are not followed by the current user
        // $suggestedPeople = User::where('id', '!=', $userId)
        //     ->whereDoesntHave('followers', function ($query) use ($userId) {
        //         $query->where('follower_id', $userId);
        //     })
        //     ->inRandomOrder()
        //     ->limit(10)
        //     ->get();
        $suggestedPeople = User::where('id', '!=', $userId)
            ->whereDoesntHave('followers', function ($query) use ($userId) {
                $query->where('follower_id', $userId);
            })
            ->whereDoesntHave('blocks', function ($query) use ($userId) {
                $query->where('blocked_user_id', $userId)->orWhere('user_id', $userId);
            })
            ->inRandomOrder()
            ->limit(10)
            ->get();

        $suggestedGroups = Group::query()
            ->select(['groups.*'])
            ->where('group_status', 'public')
            ->whereDoesntHave('currentUserGroup', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->orderBy('name', 'desc')
            ->get();

        // $followings = $user->followings()->wherePivot('status', 'accepted')->get();
        $followings = $user
            ->followings()
            ->wherePivot('status', 'accepted')
            ->whereDoesntHave('blocks', function ($query) use ($user) {
                $query->where('blocked_user_id', $user->id)->orWhere('user_id', $user->id);
            })
            ->get();

        return Inertia::render('Tribekoto/Home', [
            'posts' => $posts,
            'groups' => GroupResource::collection($groups),
            'suggestedGroups' => GroupResource::collection($suggestedGroups),
            // 'followings' => UserResource::collection($user->followings),
            'followings' => UserResource::collection($followings),
            'suggestedUsers' => UserResource::collection($suggestedPeople),
        ]);
    }

    public function policy(Request $request)
    {
        return Inertia::render('PrivacyPolicy', []);
    }

    public function terms(Request $request)
    {
        return Inertia::render('TermsOfService', []);
    }

    public function copyright(Request $request)
    {
        return Inertia::render('Copyright', []);
    }

    public function faqs(Request $request)
    {
        return Inertia::render('Faqs', []);
    }

    public function adkotoprofile(Request $request)
    {
        return Inertia::render('AdkotoProfile', []);
    }
}
