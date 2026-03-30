<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\ExpertAgentTrait;

class ImportExpertAgent extends Command
{
    use ExpertAgentTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:expertagent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import property listings from an ExpertAgent feed';

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
        // display what date/time this started
        $this->info(sprintf('Began on %s', date('r')));

        // pass to ExpertAgentTrait
        $this->import();

        // display what date/time this ended
        $this->info(sprintf('Completed on %s', date('r')));
    }
}
