<?php

namespace App\Notifications;

use App\Models\Group;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestApproved extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Group $group, public User $user, public bool $approved)
    {
        //
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
        $action = ($this->approved ? 'approved' : 'rejected');

        return (new MailMessage)
            ->subject('Request was ' . $action)
            ->line('Your request to join to group "' . $this->group->name . '" has been ' . $action)
            ->action('Open Group', url(route('group.profile', $this->group)))
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
            'group_id' => $this->group->id,
            'user_id' => $this->user->id,
            'approved' => $this->approved,
            'message' => 'Your request to join the group "' . $this->group->name . '" has been ' . ($this->approved ? 'approved' : 'rejected'),
            'route' => route('group.profile', $this->group),
            'icon' => '✅',
            // 'action_url' => url(route('group.profile', $this->group)),
        ];
    }
    // public function toArray(object $notifiable): array
    // {
    //     return [
    //         'group_id' => $this->group->id,
    //         'user_id' => $this->user->id,
    //         'approved' => $this->approved,
    //         'message' => 'Your request to join the group "' . $this->group->name . '" has been ' . ($this->approved ? 'approved' : 'rejected'),
    //     ];
    // }
}
