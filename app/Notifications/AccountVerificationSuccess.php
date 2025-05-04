<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AccountVerificationSuccess extends Notification
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
            'title_ar' => 'توثيق الحساب', // عنوان الرسالة باللغة العربية
            'title_en' => 'Account Verified', // عنوان الرسالة باللغة الإنجليزية
            'message_ar' => 'تم توثيق حسابك بنجاح.', // نص الرسالة باللغة العربية
            'message_en' => 'Your account has been successfully verified.', // نص الرسالة باللغة الإنجليزية
            'url' => 'profile/'.$this->user->id, // رابط لتوجيه المستخدم مباشرة إلى ملفه الشخصي
        ];
    }
    
}
