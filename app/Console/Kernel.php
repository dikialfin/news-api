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
        Commands\BusinessNews::class,
        Commands\EntertaimentNews::class,
        Commands\HealthNews::class,
        Commands\ScienceNews::class,
        Commands\SportsNews::class,
        Commands\TechnologyNews::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // job untuk mengambil berita terbaru
        $schedule->command('newsUpdate:business')->hourly()->timezone('asia/jakarta');
        $schedule->command('newsUpdate:entertainment')->hourly()->timezone('asia/jakarta');
        $schedule->command('newsUpdate:health')->hourly()->timezone('asia/jakarta');
        $schedule->command('newsUpdate:science')->hourly()->timezone('asia/jakarta');
        $schedule->command('newsUpdate:sports')->hourly()->timezone('asia/jakarta');
        $schedule->command('newsUpdate:technology')->hourly()->timezone('asia/jakarta');
    }
}
