<?php

namespace App\Traits;

trait TemplateTrait
{

    public function is_demo()
    {
        if(settings('is_demo') == 'Yes')
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function chosen_template()
    {
        if(settings('is_demo') == 'Yes')
        {
            return false;
        }
        else
        {
            return settings('chosen_template');
        }
    }

    public function chosen_home()
    {
        if(settings('is_demo') == 'Yes')
        {
            return false;
        }
        else
        {
            return settings('template_home');
        }
    }

    public function chosen_search()
    {
        if(settings('is_demo') == 'Yes')
        {
            return false;
        }
        else
        {
            return settings('template_search');
        }
    }

    public function chosen_details()
    {
        if(settings('is_demo') == 'Yes')
        {
            return false;
        }
        else
        {
            return settings('template_details');
        }
    }

}