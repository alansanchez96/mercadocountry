<?php

namespace App\Jobs;

use App\Models\User;
use App\Mail\SendCodeMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendCodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public readonly User $user)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $mailRegister = new SendCodeMail($this->user);
        Mail::to($this->user->email)->send($mailRegister);
    }
}
