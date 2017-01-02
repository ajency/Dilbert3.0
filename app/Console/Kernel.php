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
        // Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        /* This cron is to create new Locked_data a.k.a Taalaa_Data every night @ 10 (i.e. daily @10 PM) */
        $schedule->exec('curl http://localhost:80/api/data/save')->dailyAt('16:30');//->cron('30 16 * * * *'); /* The time is set in UTC+0:0 i.e. in IST it is 22:00 hrs */
    }
}
