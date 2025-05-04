<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;

class BillNotification extends Notification
{
    use Queueable;

    public $bill;

    /**
     * إنشاء كائن الإشعار وتمرير بيانات الفاتورة.
     */
    public function __construct($bill)
    {
        $this->bill = $bill;
    }

    /**
     * تحديد القنوات التي سيتم إرسال الإشعار من خلالها.
     */
    public function via($notifiable)
    {
        return ['database']; // سيتم تخزين الإشعار في قاعدة البيانات فقط
    }

    /**
     * تحديد البيانات التي سيتم تخزينها في قاعدة البيانات.
     */
    public function toDatabase($notifiable)
    {
        return [
            'title_ar' => 'تم إنشاء طلب جديد.', //عنوان الرسالة باللغة العربية
            'title_en' => 'Add new request', //عنوان الرسالة باللغة الانجليزية
            'message_ar' => 'تم إضافة الطلب بالمعرف: '.$this->bill->rental_request_id.' ورقم السند: '.$this->bill->id.' بقيمة: '.$this->bill->amount, //نص الرسالة باللغة العربية
            'message_en' => 'Add request with ID'.$this->bill->rental_request_id.' and bill ID: '.$this->bill->id.' in the amount of: '.$this->bill->amount, //نص الرسالة باللغة الانجليزية
            'url' => 'requests/requests/'.$this->bill->rental_request_id, // رابط لتوجيه الادمن مباشرة الى الطلب المضاف عند النقر على الاشعار
        ];
    }
}
