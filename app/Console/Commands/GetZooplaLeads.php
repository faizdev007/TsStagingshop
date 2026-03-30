<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use App\Traits\ZooplaTrait;

class GetZooplaLeads extends Command
{
    use ZooplaTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoopla:get_leads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets Email Leads from Zoopla and Stores in Messages table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        // $settings_table_exist = Schema::hasTable('settings');

        // if($settings_table_exist !== false)
        // {
        //     $this->is_set = settings('zoopla');
        // }
        // else
        // {
        //     return false;
        // }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // if($this->is_set == '1')
        // {
        //     $leads = $this->get_zoopla_leads();
        // }
    }
}
