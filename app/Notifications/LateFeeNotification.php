<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LateFeeNotification extends Notification
{
    use Queueable;

    private $adminNotficationData ;
    /**
     * Create a new notification instance.
     */
    public function __construct($adminNotficationData)
    {
        $this->adminNotficationData =$adminNotficationData;
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
            ->subject('Late Fee Incurred');

    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [

        ];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message_en'=>  $this->adminNotficationData['message_en'],
            'message_ar'=>  $this->adminNotficationData['message_ar'],
            'title_en'=>  $this->adminNotficationData['title_en'],
            'title_ar'=>  $this->adminNotficationData['title_ar'],
            'url' => $this->adminNotficationData['url'],

        ];

    }
}


