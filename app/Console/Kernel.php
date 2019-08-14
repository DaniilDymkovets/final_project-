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
        //
        Commands\updateDepositProcent::class,
        Commands\updateAllUserBalance::class,
        Commands\updateAllUsersLevel::class,
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
        //запуск очереди
        //$schedule->command('queue:work --timeout=61')->everyMinute();
        //перезапуск очереди задач
        //$schedule->command('queue:restart')->everyFiveMinutes();
        
        
        //Каждый день в 00:32 по серверному времени
        $schedule->command('updateAllUserBalance')->dailyAt('00:32');
        
        //Каждый день в 01:33 по серверному времени
        $schedule->command('updateDepositProcent')->dailyAt('01:33');
        
        //Каждый день в 02:34 по серверному времени
        $schedule->command('updateAllUsersLevel')->dailyAt('02:34');
        
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
