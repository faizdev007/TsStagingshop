<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class RunCmdController extends Controller
{
    public function index()
    {   
        try {
            Artisan::call('custom:clear-cache');
            return response()->json([
                'success' => true,
                'message' => 'All caches cleared successfully',
                'output' => Artisan::output()
            ]);
        } catch (\Exception $e) {
            Log::error('Cache clearing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear caches',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}


       // Delete and get count of deleted records
//     $deletedCount = DB::table('leads')
//     ->where('email', 'ericjonesmyemail@gmail.com')
//     ->delete();

// echo "Deleted {$deletedCount} records.<br><br>";

// // Show remaining data
// $data = DB::select("select * from users");
// echo "<pre>";
// print_r($data);

        // First, let's see all tables in the database
        // $tables = DB::select('SHOW TABLES');
        
        // echo "Available tables:<br>";
        // foreach($tables as $table) {
        //     foreach($table as $key => $value) {
        //         echo $value . "<br>";
        //     }
        // }
        
        // echo "<br>Please check the correct table name from above list and update the code accordingly.";





    //This Query for change the colunm name
        // DB::statement("ALTER TABLE properties CHANGE balcony_area_unit terrace_area_unit VARCHAR(40) NULL;");
    //This Query for change the colunm name


    //delete all data from table
        // DB::statement("DELETE FROM whatsapp_clicks;");
    //delete all data from table




/*     //create table query 
        // DB::statement("CREATE TABLE whatsapp_clicks (
            id INT NOT NULL AUTO_INCREMENT,
            ref VARCHAR(50) NULL,
            PRIMARY KEY (id)
        );");
     //create table query  */



     //This Query for Add Colunm in a Table
        // return DB::select("ALTER TABLE properties ADD COLUMN balcony_area integer NULL,
        // ADD COLUMN balcony_area_unit varchar(40) NULL;");

        //  return DB::select("ALTER TABLE leads ADD COLUMN add_notes TEXT NULL;");  // here we can add long text colunm like description and note etc
    //This Query for Add Colunm in a Table


    //This Query for view data in the table
        // $data = DB::select("select * from clients");
        // echo "<pre>";
        // print_r($data);
    //This Query for view data in the table


    //This command is for clear the cache
       // define('STDIN', fopen('php://stdin', 'r'));
        // // Run the `php artisan migrate` command.
        // Artisan::call('cache:clear');
        // // Return a success message.
        // return response()->json(['success' => true, 'message' => 'Website Cache Clean']);
    //This command is for clear the cache




    //Image resize code for web speeed
    // public function index()
    // {
    //     try {
    //         // Run the `compress:images` command.
    //         Artisan::call('compress:images');

    //         // Inside your command
    //         Log::info('Images compressed successfully.');

    //         // Return a success message.
    //         return response()->json(['success' => true, 'message' => 'Images compressed successfully']);
    //     } catch (\Exception $e) {
    //         // Log any exceptions that may occur during the command execution.
    //         Log::error('Error compressing images: ' . $e->getMessage());

    //         // Return an error message.
    //         return response()->json(['success' => false, 'message' => 'Error compressing images']);
    //     }
    // }

        //Image resize code for web speeed

        //This command for record delete from databse 
        // DB::table('property_types')->where('slug', 'any')->delete();
        // return response()->json(['success' => true, 'message' => 'Record "any" deleted']);
        //This command for record delete from databse

    //              // Update the record in the properties table
    // DB::table('properties')
    // ->where('id', 657)
    // ->update(['is_development' => 'n']);
         // Update the record in the properties table






             // public function index()
    // {
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Welcome to RunCmdController. Use specific methods to run commands.'
    //     ]);
    // }

    // public function installElasticsearch()
    // {
    //     try {
    //         $projectRoot = base_path();

    //         // Install Elasticsearch
    //         $process = new Process('composer require elasticsearch/elasticsearch:"^6.7"');
    //         $process->setWorkingDirectory($projectRoot);
    //         $process->setTimeout(300); // 5 minutes
    //         $process->run();

    //         if (!$process->isSuccessful()) {
    //             throw new ProcessFailedException($process);
    //         }

    //         // Update autoloader
    //         $dumpAutoloadProcess = new Process('composer dump-autoload');
    //         $dumpAutoloadProcess->setWorkingDirectory($projectRoot);
    //         $dumpAutoloadProcess->run();

    //         if (!$dumpAutoloadProcess->isSuccessful()) {
    //             throw new ProcessFailedException($dumpAutoloadProcess);
    //         }

    //         // Clear config cache
    //         Artisan::call('config:clear');

    //         return response()->json([
    //             'success' => true, 
    //             'message' => 'Elasticsearch installed successfully and autoloader updated',
    //             'output' => $process->getOutput() . "\n" . $dumpAutoloadProcess->getOutput()
    //         ]);
    //     } catch (\Exception $e) {
    //         Log::error('Elasticsearch installation failed: ' . $e->getMessage());
    //         return response()->json([
    //             'success' => false, 
    //             'message' => 'Failed to install Elasticsearch',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }