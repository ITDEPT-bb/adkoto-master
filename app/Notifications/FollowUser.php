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

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, bool $follow = true)
    {
        $this->user = $user;
        $this->follow = $follow;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Only send notifications if the user is followed
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
        $subject = $this->user->username . ' has followed you';
        return (new MailMessage)
            ->subject($subject)
            ->line($subject)
            ->action('View Profile', url(route('profile', $this->user)))
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
            'route' => route('profile', $this->user->username),
            'message' => $this->user->username . ' has followed you'
        ];
    }
}
