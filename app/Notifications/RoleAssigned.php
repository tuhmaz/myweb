<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RoleAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    protected $role;
    protected $permission;
    protected $action;

    /**
     * Create a new notification instance.
     */
    public function __construct($role = null, $permission = null, $action = 'assigned')
    {
        $this->role = $role;
        $this->permission = $permission;
        $this->action = $action; // إضافة نوع العملية (إضافة أو إزالة)
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $message = (new MailMessage)
                    ->subject('Role/Permission Update')
                    ->greeting('Hello ' . $notifiable->name . '!');

        if ($this->action === 'removed') {
            if ($this->role) {
                $message->line("The role '{$this->role}' has been removed from your account.");
            }

            if ($this->permission) {
                $message->line("The permission '{$this->permission}' has been removed from your account.");
            }
        } else {
            if ($this->role) {
                $message->line("You have been assigned the role '{$this->role}'.");
            }

            if ($this->permission) {
                $message->line("You have been granted the permission '{$this->permission}'.");
            }
        }

        $message->line('Thank you for being a part of our system!');

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
{
    return [
        'role' => $this->role,
        'permission' => $this->permission ?? null,  // تحقق من وجود 'permission'
        'action' => $this->action,
    ];
}

}
