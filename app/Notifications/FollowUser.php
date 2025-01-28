<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FollowUser extends Notification
{
    use Queueable;

    public User $user;
    public bool $follow;
    public string $status;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, bool $follow = true, string $status = 'accepted')
    {
        $this->user = $user;
        $this->follow = $follow;
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Only send notifications if the user is followed or a follow request is sent
        if ($this->follow) {
            return ['mail', 'database'];
        }

        // Return an empty array to avoid sending notifications if unfollowed
        return [];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->getNotificationMessage();

        return (new MailMessage)
            ->subject($subject)
            ->line($subject)
            ->action('View Profile', url(route('profile', $this->user->username)))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'user_id' => $this->user->id,
            'username' => $this->user->username,
            'follow' => $this->follow,
            // 'route' => route('profile', $this->user->username),
            'route' => $this->getRoute(),
            'message' => $this->getNotificationMessage(),
        ];
    }

    /**
     * Get the notification message based on follow status.
     *
     * @return string
     */
    private function getNotificationMessage(): string
    {
        if (!$this->follow) {
            return $this->user->name . ' has unfollowed you.';
        }

        return $this->status === 'pending'
            ? $this->user->name . ' has requested to follow you.'
            : $this->user->name . ' has followed you.';
    }

    /**
     * Get the notification route or message based on follow status.
     *
     * @return string
     */
    private function getRoute(): string
    {
        if (!$this->follow) {
            return route('profile', ['username' => $this->user->username]) . '?message=unfollowed';
        }

        if ($this->status === 'pending') {
            return route('profile.PendingRequest');
        }

        if ($this->status === 'accepted') {
            return route('profile', ['user' => $this->user->username]);
        }

        return route('dashboard');
    }

}
