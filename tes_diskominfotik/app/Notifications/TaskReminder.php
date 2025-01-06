<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskReminder extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */

    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    // Untuk database notification
    public function via($notifiable)
    {
        return ['database', 'mail']; // Bisa mengirim ke database dan email
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->task->title,
            'description' => $this->task->description,
            'deadline' => $this->task->deadline,
            'status' => $this->task->status,
        ];
    }

    // Untuk email notification (jika diperlukan)
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('You have a task reminder.')
            ->action('View Task', url('/tasks/' . $this->task->id))
            ->line('Deadline: ' . $this->task->deadline);
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
