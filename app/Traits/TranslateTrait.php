<?php

namespace App\Traits;

use App\Models\Languages;
use App\Models\Translation;

trait TranslateTrait
{
    public function get_default_language()
    {
        $languages = Languages::first();

        return $languages->language_default;
    }

    public function get_set_languages()
    {
        $languages = Languages::first();

        return $languages;
    }

    public function saveTranslation($request, $module_obj, $translationFields, $module_obj_name)
    {
        if(!empty($module_obj) && !empty($translationFields) && !empty($module_obj_name))
        {
            $set_languages = $this->get_set_languages();

            foreach($set_languages->languages_array as $language)
            {

                $is_NotEmpty = false;
                foreach( $translationFields as $transData){
                    if(!empty($transData.'_'.$language)){
                        $is_NotEmpty = true;
                    }
                }

                if( in_array('meta', $translationFields) ){

                    $metaArray = [
                      'title' => $request->input('meta_title_'.$language),
                      'description' => $request->input('meta_description_'.$language),
                      'keywords' => $request->input('meta_keywords_'.$language)
                    ];
                    $meta = json_encode($metaArray);
                    $translationFields['meta'] = $meta;
                    if(!empty($meta)){
                        $is_NotEmpty = true;
                    }
                }

                // Update Any Existing Pivots...
                $existing_trans = $module_obj->translations;

                if(!$existing_trans->isEmpty())
                {
                    foreach($existing_trans as $tran)
                    {
                        // Find The Trans
                        $update_trans = Translation::where('id', $tran->id)
                                                    ->where('language', $language)
                                                    ->first();

                        if($update_trans){
                            // Save
                            $save_trans = Translation::find($update_trans->id);

                            if( in_array('meta', $translationFields) ){
                                $save_trans->meta = $meta;
                            }else{
                                foreach( $translationFields as $transData){
                                    $save_trans->{$transData} = $request->input($transData.'_'.$language);
                                }
                            }

                            $save_trans->save();

                        }else{

                            $create_check_trans = Translation::where('translationable_id', $module_obj->id)
                                                            ->where('language', $language)
                                                            ->where('translationable_type', $module_obj_name)
                                                            ->first();

                            if(empty($create_check_trans)){
                                if( $is_NotEmpty )
                                {
                                    $translations = [
                                        'language'              => $language,
                                        'translationable_id'    => $module_obj->id,
                                        'translationable_type'  => $module_obj_name
                                    ];

                                    if( in_array('meta', $translationFields) ){
                                        $translations['meta'] = $meta;
                                    }else{
                                        foreach( $translationFields as $transData){
                                            $translations[$transData] = $request->input($transData.'_'.$language);
                                        }
                                    }

                                    $module_obj->translations()->create($translations);
                                }
                            }
                        }

                    }
                }
                else
                {
                    // Create New Translation....
                    if( $is_NotEmpty )
                    {

                        // Create The Translation....
                        $translations = [
                            'language'              => $language,
                            'translationable_id'    => $module_obj->id,
                            'translationable_type'  => $module_obj_name
                        ];

                        if( in_array('meta', $translationFields) ){
                            $translations['meta'] = $meta;
                        }else{
                            foreach( $translationFields as $transData){
                                $translations[$transData] = $request->input($transData.'_'.$language);
                            }
                        }
                        $module_obj->translations()->create($translations);
                    }
                }
            }
        }

    }

    public function removeTransInput($inputs='', $translationFields=[])
    {
        if(!empty($inputs) && count($translationFields)){
            $set_languages = $this->get_set_languages();
            foreach($set_languages->languages_array as $language){
                foreach( $translationFields as $transData){
                    unset($inputs[$transData.'_'.$language]);
                }
            }
        }
        return $inputs;
    }

}
