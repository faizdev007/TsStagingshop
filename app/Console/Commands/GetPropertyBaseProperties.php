<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Traits\PropertyBaseTrait;

class GetPropertyBaseProperties extends Command
{
    use PropertyBaseTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'propertybase:getproperties';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets all Properties from Propertybase';

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
        if(settings('propertybase'))
        {
            $this->import();
        }
    }
}
