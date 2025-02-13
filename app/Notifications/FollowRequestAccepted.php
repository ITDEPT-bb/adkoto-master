<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FollowRequestAccepted extends Notification
{
    use Queueable;

    protected $follower;
    public User $user;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $follower, User $user)
    {
        $this->follower = $follower;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
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

    //     ];
    // }

    public function toDatabase(object $notifiable): array
    {
        return [
            'user_id' => $this->user->id, // The user who accepted the follow request
            'username' => $this->user->username,
            'route' => route('profile', $this->user->username), // Assuming a profile route exists
            'icon' => '🎉',
            'message' => "{$this->user->name} {$this->user->surname} has accepted your follow request."
        ];
    }
}
