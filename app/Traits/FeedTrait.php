<?php

namespace App\Traits;

use Carbon\Carbon;
use GuzzleHttp\Client;
// use GuzzleHttp\RequestOptions;
// use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Storage;
use App\Property;
use App\PropertyMedia;

trait FeedTrait
{

    /**
     * Parse something into a trimmed string
     * @param  mixed $var
     * @return string
     */
    private function parse($var)
    {
        return trim((string) $var);
    }

    /**
     * Populate the property_media database table
     * @param array $images
     * @param Property $property
     * @param string $type
     */
    function import_images($images = [], $property = null, $type = 'photo')
    {
        // To restrict this functionality in local env:
        if (app()->environment() === 'local')
        {
            // To only process a few images:
            $images = array_slice($images, 0, 2);
            // To skip image importing in local environment:
            // return;
        }

        if (empty($property)) return; // It needs an associated property

        $image_counter = 0;

        $mediaType = 'propertyMediaPhotos';
        if ($type === 'floorplan') $mediaType = 'propertyMediaFloorplans';

        if ($property->$mediaType->count())
        {
            // Compare & Update...
            $existing_array = $property->$mediaType->pluck('path')->toArray();
            $compare_array = $images;

            // First Compare Keys...
            $differences = array_diff_assoc($existing_array, $compare_array);
            $differences2 = array_diff_assoc($compare_array, $existing_array);
            if ($differences || $differences2)
            {
                $new_sequence = 0;

                // Clear Existing
                foreach ($property->$mediaType as $PropertyMedia_id)
                {
                    $photo = PropertyMedia::find($PropertyMedia_id->id);
                    if (! empty($photo->path))
                    {
                        $image_counter++;
                        $photo->delete();
                    }
                }

                foreach ($images as $image)
                {
                    $new_sequence++;

                    // Add New Images....
                    $photo = new PropertyMedia;
                    $photo->property_id = $property->id;
                    $photo->type = $type;
                    $photo->path = $image;
                    $photo->sequence = $new_sequence;
                    $photo->save();

                    $this->info(vsprintf('Saved %s %d: %s', [
                        $type,
                        $photo->sequence,
                        $photo->path
                    ]));
                }
            }
            else return;
        }
        else
        {
            // Create New Images...
            foreach ($images as $image)
            {
                $image_counter++;

                $photo = new PropertyMedia;
                $photo->property_id = $property->id;
                $photo->type = $type;
                $photo->path = $image;
                $photo->sequence = $image_counter;
                $photo->save();
                $this->info(vsprintf('Saved %s %d: %s', [
                    $type,
                    $photo->sequence,
                    $photo->path
                ]));
            }
        }
    }

    function import_documents($documents = [], $property = null)
    {
        if (!$property) return;

        $document_counter = 0;
        $type = 'document';

        if($property->propertyMediaDocuments->count())
        {
            // Compare
            $existing_array = $property->propertyMediaDocuments->pluck('path')->toArray();

            // Compare Array.
            $compare_array = array();

            foreach($documents as $document)
            {
                $compare_array[] = $document;
            }

            // Compare Keys...
            $differences = array_diff_assoc($existing_array, $compare_array);
            if($differences)
            {
                // Clear Existing...
                foreach($property->propertyMediaDocuments as $PropertyMedia_id)
                {
                    $doc = PropertyMedia::find($PropertyMedia_id->id);

                    if (! empty($doc->path))
                    {
                        $this->info(sprintf('Delete %s for property %s: %s', $type, $property->id, $doc->path));
                        $doc->delete();
                    }
                }

                // Create New Documents...
                foreach($documents as $document)
                {
                    $document_counter++;
                    $ext = $this->get_extension($document);
                    // Add New Images....
                    $file = new PropertyMedia;
                    $file->property_id = $property->id;
                    $file->type = $type;
                    $file->extension = $ext;
                    $file->path = $document;
                    $file->sequence = $document_counter;
                    $file->save();

                    $this->info(sprintf('Saved document %s', $document));
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            // Create New Documents...
            foreach($documents as $document)
            {
                $document_counter++;
                $ext = $this->get_extension($document);
                // Add New Images....
                $file = new PropertyMedia;
                $file->property_id = $property->id;
                $file->type = $type;
                $file->extension = $ext;
                $file->path = $document;
                $file->sequence = $document_counter;
                $file->save();

                $this->info(sprintf('Saved document %s', $document));
            }
        }
    }

    private function get_extension($path = '')
    {
        // we could go on
        if (preg_match('/\.pdf$/i', $path))
        {
            return 'pdf';
        }
        elseif (preg_match('/\.doc$/i', $path))
        {
            return 'doc';
        }
        return null;
    }

    /**
     * Convert tour/video URLs
     * @param  mixed $type
     * @return string
     */
    private function remap_video_url($url)
    {
        // Change Vimeo URLs:
        // BAD:  https://vimeo.com/552420129/6e4c88a381
        // GOOD: http://player.vimeo.com/video/552420129
        $vimeo_pattern = '!^https?\:\/\/vimeo.com\/(.+)!';
        if (preg_match($vimeo_pattern, $url, $matches))
        {
            // It's a vimeo.com video and needs converting to http://player.vimeo.com as appropriate
            // In the case of 552420129/6e4c88a381, we just want the first segment
            $uri_string = $matches[1];
            $explode = explode('/', $uri_string);
            $uri_string = reset($explode);
            unset($explode);
            $url = 'https://player.vimeo.com/video/'.$uri_string;
        }

        return $url; // Nothing to change
    }

}
