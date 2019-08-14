<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\MyClasses\HelpClass\UpAllUsersActiveDeposits;
use App\MyClasses\HelpClass\UpAllActiveUsersReferals;

class updateAllUserBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateAllUserBalance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update All balance for All Users';


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
        Log::warning('--Начало, обновления баланса всех пользователей.-------------------------->'.  Carbon::now());
        $this->info('обновляем записи всех открытых депозитов всех пользователей');
        
        //обновляем записи всех открытых депозитов всех пользователей
        $up_deps = new UpAllUsersActiveDeposits();
        $up_deps->updateAllDpositsBPAllUsers();
        
        $this->info('обновляем реферальную систему');
        //обновляем реферальную систему
        $up_ref = new UpAllActiveUsersReferals();
        $up_ref->allUpdate();
        

        $this->info('Завершено, обновления баланса всех пользователей.->'.  Carbon::now());
        Log::warning('--Завершено, обновления баланса всех пользователей.-------------------------->'.  Carbon::now());        
    }

}
