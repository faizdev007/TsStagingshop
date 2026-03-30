<?php

namespace App;

use Illuminate\Support\Facades\DB;

class CustomProperty extends Property
{
    /**
     * Override the searchWhere method to handle special status properties
     */
    public function searchWhere($criteria = [], $dashboard = FALSE, $strict_limit = FALSE)
    {
        // Get the original properties from the parent method
        $originalProperties = parent::searchWhere($criteria, $dashboard, $strict_limit);
        
        // Get the current page and last page
        $currentPage = request()->input('page', 1);
        $lastPage = $originalProperties->lastPage();
        
        // If we're on the last page, return all properties
        if ($currentPage == $lastPage) {
            return $originalProperties;
        }
        
        // If we're not on the last page, filter out properties with special status
        $filteredProperties = $originalProperties->filter(function($property) {
            $status = strtolower($property->property_status ?? '');
            return strpos($status, 'sold / request similar') === false && 
                   strpos($status, 'rented / request similar') === false;
        });
        
        return $filteredProperties;
    }
}
