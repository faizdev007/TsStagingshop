<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\RightMoveTrait;
use App\Property;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use QCod\AppSettings\Setting\AppSettings;


class UpdateRightMove extends Command
{
    use RightMoveTrait
    {
        RightMoveTrait::__construct as private _rightMoveTraitConstruct;
    }

    private $is_set;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rightmove:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates Rightmove - Creates / Updates and Deletes properties from the Rightmove API';

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
            $this->is_set = settings('rightmove');
        }
        else
        {
            return false;
        }

        $this->_rightMoveTraitConstruct();
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
            // Our Rightmove Logging File...
            $file = 'logs/rightmove.log';

            // Rightmove is Enabled via settings (Run Command)...
            $now = Carbon::now();

            // Returns date & time stamp from .log file for last ran..
            $last_ran = @file_get_contents(storage_path($file));
            //$last_ran = Carbon::now()->subHours(18); // Debugging...

            if($last_ran == NULL && env('APP_ENV' !== 'local') && env('APP_ENV' !== 'staging'))
            {
                // Send ALL Properties to Rightmove, First Time Ran....
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
                //$to_date = Carbon::now()->subHours($time_diff);
                $to_date = Carbon::now()->subHours('1');

                // Get Filtered Properties & One's added / updated in last 12 hours...
                $properties = Property::whereNotNull('ref')
                    ->where('beds', '>', 0 )
                    ->where('price', '>', 0)
                    ->where('name', '!=', '')
                    ->where('city', '!=', '')
                    ->whereRaw('LENGTH(postcode) >= 5')
                    ->whereBetween('created_at', [$to_date, $from_date])
                    ->orWhereBetween('updated_at', [$to_date, $from_date])
                    ->withTrashed()
                    ->get();
            }

            if($properties->count() > 0)
            {
                // Only Send If we have Properties...

                // Get Settings....
                if(settings('overseas') == 0)
                {
                    // UK Based....
                    $this->update_feed($properties);
                }
                else
                {
                    // Overseas....
                    $this->update_feed($properties, true);
                }
            }

            // Always Save TimeStamp to Laravel Log file....
            file_put_contents(storage_path($file), Carbon::now()->toDateTimeString());
        }
    }
}
