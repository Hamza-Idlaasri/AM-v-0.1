<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // 'App\Console\Commands\SendEmail',
        'App\Console\Commands\SendHostEmail',
        'App\Console\Commands\SendServiceEmail',
        'App\Console\Commands\SendBoxEmail',
        'App\Console\Commands\SendEquipEmail'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->command('notif:email')
        //     ->everyMinute();

        $schedule->command('notif:box')
            ->everyMinute();

        $schedule->command('notif:host')
            ->everyMinute();

        $schedule->command('notif:service')
            ->everyMinute();
    
        $schedule->command('notif:equip')
            ->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
