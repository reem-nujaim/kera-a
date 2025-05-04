<?php


namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AccountVerificationRequest extends Notification
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
            'title_ar' => 'طلب توثيق حساب', // عنوان الرسالة باللغة العربية
            'title_en' => 'Account Verification Request', // عنوان الرسالة باللغة الإنجليزية
            'message_ar' => 'تم إرسال طلب توثيق الحساب للمراجعة.', // نص الرسالة باللغة العربية
            'message_en' => 'The account verification request has been sent for review.', // نص الرسالة باللغة الإنجليزية
            'url' => 'customers/'.$this->user->id.'/verify', // رابط لتوجيه المستخدم مباشرة لطلب التوثيق
        ];
    }
    
}
