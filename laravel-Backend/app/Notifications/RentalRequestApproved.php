<?php

namespace App\Notifications;


use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\RentalRequest;



class RentalRequestApproved extends Notification
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
    
            'title_ar' => 'تم قبول طلب الإيجار', // عنوان الرسالة باللغة العربية
            'title_en' => 'Rental Request Approved', // عنوان الرسالة باللغة الإنجليزية
            'message_ar' => "تم قبول طلبك لاستئجار الغرض: {$this->rentalRequest->item->name}.", // نص الرسالة باللغة العربية
            'message_en' => "Your request to rent the item: {$this->rentalRequest->item->name} has been approved.", // نص الرسالة باللغة الإنجليزية
            'rental_request_id' => $this->rentalRequest->id,
            'url' => 'rental/requests/'.$this->rentalRequest->id, // رابط لتوجيه المستخدم مباشرة إلى تفاصيل طلب الإيجار
        ];
    }
    
}
