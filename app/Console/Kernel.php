<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\User;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        // جدولة حذف الإشعارات غير المقروءة بعد 3 أيام
        $schedule->call(function () {
            $this->deleteOldUnreadNotifications();
        })->daily();
    }

    /**
     * Delete unread notifications older than 3 days.
     */
    protected function deleteOldUnreadNotifications()
    {
        // افترض أن المستخدم لديه إشعارات
        User::chunk(100, function ($users) {
            foreach ($users as $user) {
                $user->notifications()
                    ->whereNull('read_at')
                    ->where('created_at', '<=', now()->subDays(3))
                    ->delete();
            }
        });
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
