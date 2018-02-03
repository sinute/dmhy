<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\DMHY\Fetch',
        'App\Console\Commands\Weibo\Publish',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('dmhy:fetch', ['url', 'https://share.dmhy.org/'])->cron('*/20 * * * *');
        $schedule->command('weibo:push')->cron('*/15 * * * *');
    }
}
