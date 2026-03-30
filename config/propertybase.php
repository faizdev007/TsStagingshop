<?php

return [
    'endpoint' => 'https://altmanrealestate.secure.force.com/pba__WebserviceListingsQuery',
    'token' => '45ffc4e958802a1a2e1a788eccdbde72',
    'format' => 'json',
    'debugmode' => 'true',
    'getDocuments' => 'true',
    'getImages' => 'true',
    // Fields Needed to build a Property on Our Website...
    'fields' => [
        'id',
        'Name',
        'pba__ListingType__c', // Is Rental
        'pba__PropertyType__c', // Property Type
        'pba__MonthlyRent_pb__c', // Monthly Rent
        'pba__Street_pb__c', // Street
        'pba__Address_pb__c', // Address
        'pba__City_pb__c', // City (Could Be Town at Our End)...
        'pba__AddressText_pb__c', // Text Version Of Address?
        'pba__State_pb__c', // State (Region in our DB)
        'pba__County_pb__c', // County
        'pba__PostalCode_pb__c', // Zip / Postcode
        'pba__Country_pb__c', // Country
        'pba__Latitude_pb__c', // Latitude
        'pba__Latitude_Property__c', // Latitude
        'pba__Longitude_Property__c', // Long
        'pba__Longitude_pb__c', // Longitude
        'pba__ListingPrice_pb__c', // Price
        // Price Qualifier?
        'pba__Bedrooms_pb__c', // Beds
        'pba__FullBathrooms_pb__c', // Baths
        'pba__LotSize_pb__c', // Area
        'pba__Status__c', // Property Status
        'pba__TotalArea_pb__c', // Internal Area?
        'pba__Description_pb__c', // Description
    ],
    // Custom Fields (Will be a on a per client basis)
    'custom' =>
        [
            'pba__SystemAllowedForPortals__c',
            'Property_Sub_Type_2__c',
            'Location__c', // Community Field....
            'Amenities__c',
            'Pick_of_the_month__c',
            'Key_Features__c',
            'Listing_Price_Minimum__c',
            'Listing_Price_Maximum__c',
        ]
];
