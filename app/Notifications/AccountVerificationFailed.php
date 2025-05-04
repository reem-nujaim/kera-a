<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AccountVerificationFailed extends Notification
{
    use Queueable;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'user_id' => $this->user->id,
            'title_ar' => 'رفض طلب التوثيق', // عنوان الرسالة باللغة العربية
            'title_en' => 'Verification Request Rejected', // عنوان الرسالة باللغة الإنجليزية
            'message_ar' => 'تم رفض طلب توثيق حسابك.', // نص الرسالة باللغة العربية
            'message_en' => 'Your account verification request has been rejected.', // نص الرسالة باللغة الإنجليزية
            'url' => 'verification/requests/'.$this->user->id, // رابط لتوجيه المستخدم مباشرة لطلب التوثيق
        ];
    }
    
}
