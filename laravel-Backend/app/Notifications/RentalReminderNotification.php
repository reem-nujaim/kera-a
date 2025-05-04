<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RentalReminderNotification extends Notification
{
    use Queueable;

 public $rentalRequest;

    public function __construct($rentalRequest)
    {
        $this->rentalRequest = $rentalRequest;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [

            'title_ar' => "تذكير بانتهاء الإيجار", // عنوان الرسالة باللغة العربية
            'title_en' => "Rental Period Ending Reminder", // عنوان الرسالة باللغة الإنجليزية
            'message_ar' => "تذكير بانتهاء مدة الإيجار قريبًا", // نص الرسالة باللغة العربية
            'message_en' => "Reminder: Your rental period is ending soon", // نص الرسالة باللغة الإنجليزية
            'rental_request_id' => $this->rentalRequest->id,
            'url' => 'rental/requests/'.$this->rentalRequest->id, // رابط لتوجيه المستخدم مباشرة إلى تفاصيل طلب الإيجار
        ];
    }

}




