<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class DataChangeEmailNotification extends Notification
{
    use Queueable;

    public function __construct($data)
    {
        $this->data = $data;
        $this->ticket = $data['ticket'];
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return $this->getMessage();
    }

    public function getMessage()
    {
        return (new MailMessage)
            ->subject($this->data['action'], $this->ticket->id)
            ->greeting('Hi,')
            ->line($this->data['action'])
            ->line("Order ID: ".$this->ticket->title)
            ->line("Brief description: ".Str::limit($this->ticket->Text, 200))
            ->action('View full ticket', route('admin.tickets.show', $this->ticket->id))
            ->line('Thank you')
            ->line(config('app.name') . ' Team')
            ->salutation(' ');
    }
}
