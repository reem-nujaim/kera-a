<?php

namespace App\Console\Commands;

use App\Models\LateFee;
use App\Models\RentalRequest;
use App\Models\User;
use App\Notifications\LateFeeNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class CreateLateFee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-late-fee';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $rentalRequests = RentalRequest::where('status', 'approved')
            ->where('end_date', '<', now())
            ->doesntHave('latefee') // تجنب التكرار
            ->get();

        foreach ($rentalRequests as $rentalRequest) {
            Log::info('CreateLateFee job started for Rental Request ID: ' . $rentalRequest->id);

            // التحقق من الحالة إذا كانت "approved" وانتهت مدة الإيجار
            if ($rentalRequest->status === 'approved' && $rentalRequest->end_date < Carbon::now()) {

                // التحقق من التأمين
                $assurance = $rentalRequest->assurance; // علاقة طلب الإيجار بالتأمين

                // إذا لم يتم إرجاع التأمين
                if ($assurance && !$assurance->is_returned) {
                    Log::info('Assurance not returned for Rental Request ID: ' . $rentalRequest->id);

                    // حساب عدد الساعات المتأخرة
                    $now = Carbon::now();
                    $endDate = Carbon::parse($rentalRequest->end_date);
                    $lateHours = $endDate->floatDiffInRealHours($now); // احسب من الدقيقة الأولى

                    // رسوم التأخير لكل ساعة من جدول العناصر
                    $feePerHour = $rentalRequest->item->price_per_hour ?? 0;

                    // حساب إجمالي رسوم التأخير
                    $totalFee = $lateHours * $feePerHour;
                    DB::beginTransaction();
                    try {
            LateFee::create([
                'number_of_late_hours' => $lateHours,
                'fee_per_late_hour' => $feePerHour,
                'total_fee' => $totalFee,
                'late_fee_date' => $now,
                'paid' => false, // افتراضيًا لم يتم دفعها
                'rental_request_id' => $rentalRequest->id,
            ]);

            $message_ar = "تم إضافة متأخر ";
            $message_en = "Add Late Fee";
            $title_ar = "متأخر جديد";
            $title_en = "new Late fee";
            $url='lateFees';

            $data = new LateFeeNotification([
                'message_en' => $message_en,
                'message_ar' => $message_ar,
                'title_en' => $title_en,
                'title_ar' => $title_ar,
                'url' => $url,
            ]);

            $admins = User::role('admin')->get();

             Notification::send($admins, $data);

            DB::commit();
                    // إضافة البيانات إلى جدول المتأخرات
        Log::info('Late fee created successfully for Rental Request ID: ' . $rentalRequest->id);
         } catch (\Exception $e) {
            DB::rollBack();
        }
                } else {
                    Log::info('Assurance already returned for Rental Request ID: ' . $rentalRequest->id);
                }
            } else {
                Log::info('Late fee calculation skipped for Rental Request ID: ' . $rentalRequest->id . ' due to invalid status or end date.');
            }
        }

    }
}
