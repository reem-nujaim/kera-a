<?php

namespace App\Console\Commands;

use App\Models\RentalRequest;
use App\Models\Assurance;
use App\Notifications\RentalReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;

class SendRentalReminders extends Command
{
    protected $signature = 'app:send-rental-reminders';
    protected $description = 'Send reminders for rental requests nearing their end date if the assurance has not been returned.';

    public function handle()
    {
        $now = Carbon::now();

        $rentalRequests = RentalRequest::where('status', 'approved')
            ->whereNotNull('end_date') // التأكد من أن هناك تاريخ نهاية
            ->whereHas('assurance', function ($query) {
                $query->where('is_returned', false); // التأمين لم يتم إرجاعه بعد
            })
            ->get();

        foreach ($rentalRequests as $rentalRequest) {
            Log::info('Checking reminder for Rental Request ID: ' . $rentalRequest->id);

            $startDate = Carbon::parse($rentalRequest->start_date);
            $endDate = Carbon::parse($rentalRequest->end_date);

            if (!$endDate) {
                Log::warning('Skipping Rental Request ID: ' . $rentalRequest->id . ' due to missing end_date.');
                continue;
            }

            $durationInHours = $startDate->diffInHours($endDate);

            // تحديد وقت التذكير بناءً على مدة الاستئجار
            $reminderTime = $durationInHours > 24
                ? $endDate->copy()->subHours(24) // إذا كانت المدة أكثر من 24 ساعة، التذكير قبل 24 ساعة
                : $endDate->copy()->subHours(5); // إذا كانت 24 ساعة أو أقل، التذكير قبل 5 ساعات

            // التحقق مما إذا كان قد تم إرسال إشعار من قبل
            $notificationExists = DB::table('notifications')
                ->where('notifiable_id', $rentalRequest->user_id) // البحث عن إشعار لهذا المستخدم
                ->where('notifiable_type', 'App\Models\User') // التأكد من أنه إشعار لمستخدم
                ->where('type', 'App\Notifications\RentalReminderNotification') // البحث عن إشعارات التذكير فقط
                ->whereJsonContains('data->rental_request_id', $rentalRequest->id) // التحقق من أن الطلب يطابق البيانات المخزنة
                ->exists();

            if (!$notificationExists && $now->greaterThanOrEqualTo($reminderTime)) {
                Notification::send($rentalRequest->user, new RentalReminderNotification($rentalRequest));

                Log::info('Reminder sent successfully to User ID: ' . $rentalRequest->user->id . ' (Name: ' . $rentalRequest->user->first_name . ') for Rental Request ID: ' . $rentalRequest->id);

            } else {
                Log::info('Reminder already sent for Rental Request ID: ' . $rentalRequest->id . ', skipping.');
            }
        }
    }
}
