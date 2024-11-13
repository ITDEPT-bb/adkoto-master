<?php

namespace App\Notifications;

use App\Models\Group;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvitationInGroup extends Notification
{
    use Queueable;

    public User $user;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, public Group $group, public int $hours, public string $token)
    {
        $this->user = $user;
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
        return (new MailMessage)
            ->line('You have been invited to join to group "' . $this->group->name . '"')
            ->action('Join the Group', url(route('group.approveInvitation', $this->token)))
            ->line('The link will be valid for next ' . $this->hours . ' hours');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'group_id' => $this->group->id,
            'user_id' => $this->user->id,
            'group_name' => $this->group->name,
            'hours' => $this->hours,
            'token' => $this->token,
            'route' => route('group.approveInvitation', $this->token),
            'message' => 'You have been invited to join the group "' . $this->group->name . '".',
        ];
    }
}
