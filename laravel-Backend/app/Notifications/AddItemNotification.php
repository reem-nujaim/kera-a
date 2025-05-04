<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class AddItemNotification extends Notification
{
    use Queueable;

    public $item;

    /**
     * Create a new notification instance.
     */
    public function __construct($item)
    {
        $this->item = $item;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database']; // يتم تخزين الإشعار في قاعدة البيانات
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'title_ar' => 'تم إضافة غرض جديد', //عنوان الرسالة باللغة العربية
            'title_en' => 'Add new item', //عنوان الرسالة باللغة الانجليزية
            'message_ar' => 'تم إضافة الغرض بالمعرف: '.$this->item->id.' واسم الغرض: '.$this->item->name.' بواسطة: '.$this->item->user->first_name, //نص الرسالة باللغة العربية
            'message_en' => 'Add item with ID'.$this->item->id.' and item name: '.$this->item->name.' by: '.$this->item->user->first_name, //نص الرسالة باللغة الانجليزية
            'url' => 'items/items/'.$this->item->id, // رابط
        ];
    }
}
