<?php

namespace App\Jobs;

use App\Models\RentalRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyBeforeRentalEnd implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $rentalRequest;
    public function __construct(RentalRequest $rentalRequest)
    {
        $this->rentalRequest = $rentalRequest;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = $this->rentalRequest->user;
        $end_date = $this->rentalRequest->end_date;

        // إرسال الإشعار إلى العميل (البريد أو الإشعار داخل التطبيق)
        // \Mail::to($user->email)->send(new \App\Mail\RentalReminder($this->rentalRequest));
    }
}
