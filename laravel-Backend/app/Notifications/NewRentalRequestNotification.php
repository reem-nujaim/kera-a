<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewRentalRequestNotification extends Notification
{
    use Queueable;

    public $item;

    public function __construct($item)
    {
        $this->item = $item;
    }

    public function via($notifiable)
    {
        return ['database']; // حفظ الإشعار في قاعدة البيانات
    }

    public function toDatabase($notifiable)
    {
        return [
            'title_ar' => 'طلب إيجار جديد', // عنوان الرسالة باللغة العربية
            'title_en' => 'New Rental Request', // عنوان الرسالة باللغة الإنجليزية
            'message_ar' => "لقد تلقيت طلب إيجار جديد للغرض: {$this->item->name}.", // نص الرسالة باللغة العربية
            'message_en' => "You have received a new rental request for the item: {$this->item->name}.", // نص الرسالة باللغة الإنجليزية
            'item_id' => $this->item->id,
            'url' => 'rental/requests/'.$this->item->id, // رابط لتوجيه المستخدم مباشرة إلى طلب الإيجار
        ];
    }
    
}
