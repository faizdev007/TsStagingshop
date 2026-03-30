<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GetLatestCurrencyRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:latest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets the latest currency codes';

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
        $base_currency = settings('currency');
        $currency = @file_get_contents('https://open.er-api.com/v6/latest/AED');
        
        if($currency){
            $inarray = json_decode($currency);
            $array = [];
            $array['rates'] = $inarray->rates;
            $array['base'] = $base_currency;
            $data = json_encode($array);
    
            if($data)
            {
                Storage::disk('public')->put('currency-rates.json', $data);
            }
        }
    }
}

//$data = @file_get_contents('https://api.exchangeratesapi.io/latest?base=EUR');
//$data = @file_get_contents('https://api.exchangeratesapi.io/latest?base='.$base_currency);
// $bbd = @file_get_contents('https://free.currconv.com/api/v7/convert?q=USD_BBD&compact=ultra&apiKey=063949b279990110110d');
// $eur = @file_get_contents('https://free.currconv.com/api/v7/convert?q=USD_EUR&compact=ultra&apiKey=063949b279990110110d');
// $gbp = @file_get_contents('https://free.currconv.com/api/v7/convert?q=USD_GBP&compact=ultra&apiKey=063949b279990110110d');

// $bbdArray = json_decode($bbd, TRUE);
// $eurArray = json_decode($eur, TRUE);
// $gbpArray = json_decode($gbp, TRUE);

// $array['rates'] =  [
//             'USD' => 1,
//             'BBD' => (!empty($bbdArray['USD_BBD']))?$bbdArray['USD_BBD']:1,
//             'EUR' => (!empty($eurArray['USD_EUR']))?$eurArray['USD_EUR']:0.841925,
//             'GBP' => (!empty($gbpArray['USD_GBP']))?$gbpArray['USD_GBP']:0.754785,
//         ];
