<?php

namespace App\Console\Commands;

use App\Traits\RightMoveTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use App\Enquiry;

class GetRightMoveLeads extends Command
{

    use RightMoveTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rightmove:get_leads {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets Email Leads from Rightmove and Stores in Messages table';

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
        // if($this->is_set == 1)
        // {
        //     // Get Lead Type Based on Argument...
        //     $lead_type = $this->argument('type');

        //     $this->get_leads($lead_type);
        // }
    }
}
