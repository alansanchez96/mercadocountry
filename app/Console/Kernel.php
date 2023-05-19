<?php

namespace App\Console;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            $expirationTime = Carbon::now()->subMinutes(15); // Minutos del temporizador

            User::whereNull('email_verified_at')
                ->whereNotNull('code')
                ->where('created_at', '<', $expirationTime)
                ->each(fn (User $user) => $user->delete());
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
