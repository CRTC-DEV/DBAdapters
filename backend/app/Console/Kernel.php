<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
{
    // Lên lịch chạy command xử lý XML mỗi 5 giây
    $schedule->command('xml:process-directory')->everyFiveSeconds();

    // Lên lịch chạy command dọn dẹp file cũ mỗi ngày
    $schedule->command('files:cleanup-processed')->daily();
}

    
    
    

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
