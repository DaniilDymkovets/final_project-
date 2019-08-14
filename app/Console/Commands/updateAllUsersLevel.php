<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class updateAllUsersLevel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateAllUsersLevel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::warning('--Начало, обновления реферальных уровней всех пользователей.-------------------------->'.  Carbon::now());
        $this->info('Начало, обновления реферальных уровней всех пользователей');
        
        $users = User::get(['id'])->pluck('id');
        foreach ($users as $user_id) {
            //отправили в работу
            dispatch(new \App\Jobs\recalculateUserReferalLevelJob($user_id));
        }
        $this->info('Завершено, обновления реферальных уровней всех пользователей.->'.  Carbon::now());
        Log::warning('--Завершено, обновления реферальных уровней всех пользователей.-------------------------->'.  Carbon::now());  
    }
}
