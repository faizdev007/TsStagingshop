<?php

namespace App\Console\Commands;

use App\Property;
use App\Traits\ZooplaTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class UpdateZoopla extends Command
{
    use ZooplaTrait
    {
        ZooplaTrait::__construct as private _zooplaTraitConstruct;
    }

    private $is_set;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoopla:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates Zoopla - Creates / Updates and Deletes properties from the Zoopla API';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $settings_table_exist = Schema::hasTable('settings');

        if($settings_table_exist !== false)
        {
            $this->is_set = settings('zoopla');
        }
        else
        {
            return false;
        }

        $this->_zooplaTraitConstruct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if($this->is_set == 1)
        {
            // Our Zoopla Logging File...
            $file = 'logs/zoopla.log';

            // Rightmove is Enabled via settings (Run Command)...
            $now = Carbon::now();

            // Returns date & time stamp from .log file for last ran..
            //$last_ran = @file_get_contents(storage_path($file));
            $last_ran = Carbon::now()->subDays(18); // Debugging...

            if($last_ran == NULL && env('APP_ENV' !== 'local') && env('APP_ENV' !== 'staging'))
            {
                // Send ALL Properties to Zoopla....
                $properties = Property::whereNotNull('ref')
                    ->where('beds', '>', 0 )
                    ->where('price', '>', 0)
                    ->where('name', '!=', '')
                    ->where('city', '!=', '')
                    ->whereRaw('LENGTH(postcode) >= 5')
                    ->get();
            }
            else
            {
                $from_date = Carbon::now();

                if($last_ran == null)
                {
                    $last_ran = Carbon::now()->subHours(2);
                }

                // Get a Difference between last ran and now...
                $time_diff = $from_date->diffInHours($last_ran);

                // This will run and get properties that have changed since the last task ran
                $to_date = Carbon::now()->subHours($time_diff);

                // Get Filtered Properties & One's added / updated in last 12 hours...
                $properties = Property::whereNotNull('ref')
                    ->where('beds', '>', 0 )
                    ->where('price', '>', 0)
                    ->where('name', '!=', '')
                    ->where('city', '!=', '')
                    ->whereRaw('LENGTH(postcode) >= 5')
                    ->whereBetween('created_at', [$to_date, $from_date])
                    ->orWhereBetween('updated_at', [$to_date, $from_date])
                    ->get();
            }

            if($properties->count() > 0)
            {
                // Only Send If we have Properties...
                $this->update_feed($properties);
            }

            // Always Save TimeStamp to Laravel Log file....
            file_put_contents(storage_path($file), Carbon::now()->toDateTimeString());
        }
    }
}
