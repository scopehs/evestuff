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
        Commands\UpdateAlliances::class,
        Commands\UpdateCampaigns::class,
        Commands\UpdateTimers::class,
        Commands\ClearRememberToken::class,
        Commands\getEsiStatus::class,
        Commands\newCampaignUpdate::class,
        Commands\newNotificationUpdate::class,
        Commands\UpdateEveUserCount::class,
        Commands\updateOpertationUserList::class,
        Commands\UpdateStanding::class,
        Commands\CleanUpCoordShhet::class,
        Commands\UpdateTowers::class,
        Commands\UpdateReconStations::class,
        Commands\UpdateWebWayRoutes::class,
        Commands\getDankDocsCommand::class,
        Commands\newnewnewcampaignsupdate::class,
        Commands\updateGroupAndCat::class,
        Commands\updateItems::class,
        Commands\DeleteOldDscans::class,
        Commands\UpdateCharInfo::class,
    ];

    /**
     * Define the application's command schedule.ddddd update all the things
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('update:EveEsiStatus')->everyMinute();
        $schedule->command('update:newnewnewCampaigns')->everyMinute();
        $schedule->command('update:newnotifications')->everyMinute();
        $schedule->command('update:DankOps')->everyMinute();
        $schedule->command('update:eveusercount')->everyMinute();
        $schedule->command('update:opuserlist')->everyMinute();
        $schedule->command('update:standing')->everyTenMinutes();
        $schedule->command('clean:coordsheet')->everyMinute();
        $schedule->command('update:towers')->everyMinute();
        $schedule->command('update:reconstations')->everyTenMinutes();
        // $schedule->command('horizon:snapshot')->everyFiveMinutes();
        $schedule->command('update:webway')->everyTenMinutes();
        $schedule->command('update:timers')->hourly();
        $schedule->command('update:charInfo')->hourly();
        $schedule->command('clean:removeOldDscans')->hourly();
        $schedule->command('update:alliances')->dailyAt('22:00');
        $schedule->command('clear:remembertoken')->twiceDaily(9, 21);
        $schedule->command('get:dankdocs')->twiceDaily(9, 21);
        $schedule->command('update:updateCatGroup')->daily();
        $schedule->command('update:items')->dailyAt('00:30');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
