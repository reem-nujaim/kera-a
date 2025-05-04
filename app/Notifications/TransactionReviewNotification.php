<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\RentalRequest;

class TransactionReviewNotification extends Notification
{
    use Queueable;

    public $rentalRequest;

    public function __construct($rentalRequest)
    {
        $this->rentalRequest = $rentalRequest;
    }

    public function via($notifiable)
    {
        return ['database']; // الإشعار سيكون مخزن في قاعدة البيانات
    }

    public function toDatabase($notifiable)
    {
        return [
            'title_ar' => 'طلب مراجعة حوالة بنكية', // عنوان الرسالة باللغة العربية
            'title_en' => 'Bank Transfer Review Request', // عنوان الرسالة باللغة الإنجليزية
            'message_ar' => "تم إضافة رقم حوالة جديد لطلب الإيجار الخاص بالغرض: {$this->rentalRequest->item->name}. يرجى مراجعته.", // نص الرسالة باللغة العربية
            'message_en' => "A new transaction number has been added for the rental request of the item: {$this->rentalRequest->item->name}. Please review it.", // نص الرسالة باللغة الإنجليزية
            'rental_request_id' => $this->rentalRequest->id,
            'url' => 'rental/transactions/'.$this->rentalRequest->id, // رابط لتوجيه المستخدم مباشرة إلى تفاصيل الحوالة البنكية
        ];
    }
    
}
