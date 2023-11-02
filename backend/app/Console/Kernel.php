<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by yours application
     * 
     * @var array
     */
    protected $commands = [
        //
        Commands\NewsCron::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('news:cron')->cron(strval(date('i')+ 1). ' ' . strval(date('h')+ 4) . ' ' . date('d') . ' ' . date('m') . ' ' . date('w'));
        $schedule->command('news:cron')->everyMinute();
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
