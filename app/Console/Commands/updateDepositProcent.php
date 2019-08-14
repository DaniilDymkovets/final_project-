<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class updateDepositProcent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateDepositProcent {--test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновление процентов по дипазитам';

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
        $this->info('----------Начало обновления процентов по всем активным дипозитам.'.($this->option('test')?' Режим Тест':'').' -------------------------->'.  Carbon::now());
        Log::warning('----------Начало обновления процентов по всем активным дипозитам.'.($this->option('test')?' Режим Тест':'').' -------------------------->'.  Carbon::now());
        $x_obj = new \App\MyClasses\UpdateUsersDeposit($this->option('test'));
        $x_obj->calculateAllDepositProcent();
        Log::warning('----------Проценты по всем дипозитам обновлены.'.($this->option('test')?' Режим Тест':'').' -------------------------->' .  Carbon::now());
        $this->info('----------Проценты по всем дипозитам обновлены.'.($this->option('test')?' Режим Тест':'').' -------------------------->' .  Carbon::now());
    }
}
