<?php

namespace App\Traits;

trait ImageTrait
{
    function get_image_orientation($path)
    {
        //$orientation = 'landscape';

        if(!empty($path))
        {
            @list($width, $height) = getimagesize(($path));

            if ($width > $height)
            {
                $orientation = 'landscape';
            }
            else
            {
                $orientation = 'portrait';
            }
        }

        return $orientation;
    }
}
