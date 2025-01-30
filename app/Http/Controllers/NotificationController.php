<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->take(5)->get();
        return response()->json($notifications);
    }

    public function fetchAllNotifications()
    {
        $notifications = Auth::user()->notifications()->latest()->get();
        $userIds = $notifications->pluck('data.user_id')->filter()->unique();

        // Fetch user profiles
        $userProfiles = User::whereIn('id', $userIds)->get()->keyBy('id');

        return Inertia::render('Tribekoto/Notifications', [
            'notifications' => $notifications,
            'userProfiles' => $userProfiles->map(fn($user) => new UserResource($user))->all()
        ]);
    }

    public function markAsRead($id)
    {
        // Find the notification for the authenticated user
        $notification = Auth::user()->notifications()->findOrFail($id);

        // Mark the notification as read
        $notification->markAsRead();

        return response()->json(['message' => 'Notification marked as read.']);
    }

    public function markAllAsRead(Request $request)
    {
        $user = auth()->user();

        $user->unreadNotifications->markAsRead();
        return to_route('dashboard')->with('success', 'Notification marked as read.');
    }

    public function deleteAllNotif(Request $request)
    {
        $user = auth()->user();

        $user->notifications()->delete();
        return to_route('dashboard')->with('success', 'All Notifications Deleted.');
    }
}

