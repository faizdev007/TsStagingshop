<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Languages;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class LanguagesController extends Controller
{
    public function index()
    {
        $languages = Languages::first();

        return view('backend.languages.index',
            [
                'settings'  => Languages::first(),
                'languages' => config('custom.languages'),
                'pageTitle' => 'Language Settings'
            ]
        );
    }

    public function save(Request $request)
    {
        // validate
        $rules = array(
            'language_default' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
        {
            $request->session()->flash('message_danger', 'Please choose a default language');

            return redirect()->back()->withInput();
        }
        else
        {
            // Existing Languages Setting - Update..
            $existing = Languages::first();

            if($existing)
            {
                $existing->language_default = $request->input('language_default');
                if($request->input('language_settings') != '')
                {
                    $existing->language_settings = $this->make_string($request->input('language_settings'));
                }
                $existing->save();
            }
            else
            {
                // Save The Language Opts...
                $language = new Languages;
                $language->language_default = $request->input('language_default');
                if($request->input('language_settings') != '')
                {
                    $language->language_settings = $this->make_string($request->input('language_settings'));
                }
                $language->save();
            }

            // redirect
            $request->session()->flash('message_success', 'Successfully saved languages!');
            return redirect()->back();
        }
    }

    function make_string($array)
    {
        $string = '';

        foreach($array as $a)
        {
            $string .= $a.',';
        }

        $new_string = substr($string,0,-1);

        return $new_string;
    }
}
