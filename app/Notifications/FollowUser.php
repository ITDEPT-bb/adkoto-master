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
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        if ($this->follow) {
            $subject = 'User "' . $this->user->username . '" has followed you';
        } else {
            $subject = 'User "' . $this->user->username . '" is no more following you';
        }
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
    // public function toArray(object $notifiable): array
    // {
    //     return [
            //
    //     ];
    // }
    public function toDatabase(object $notifiable): array
    {
        return [
            'user_id' => $this->user->id,
            'username' => $this->user->username,
            'follow' => $this->follow,
            'message' => $this->follow ? 'User "' . $this->user->username . '" has followed you' : 'User "' . $this->user->username . '" is no more following you'
        ];
    }
}
