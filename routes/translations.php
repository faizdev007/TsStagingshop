<?php

// Route::group(['prefix' => LaravelLocalization::setLocale()], function()
// {
//     /*
//     * Pages routes
//     */

//     Route::get('/', 'PagesController@index')->name('home');
//     Route::get('/about-us', 'PagesController@view');
//     Route::get('/contact-us', 'PagesController@contact');
//     Route::get('/privacy-policy', 'PagesController@view');
//     Route::get('/disclaimer', 'PagesController@view');
//     Route::get('/terms', 'PagesController@view');
//     Route::get('/valuation', 'PagesController@valuation');
//     Route::get('/testimonials', 'PagesController@testimonials');
//     Route::get('/sell', 'PagesController@view');
//     Route::get('/unsubscribe', 'PagesController@view');

//     /*
//     * News routes
//     */
//     Route::get('/news', 'NewsController@index');
//     Route::get('/article/{slug}', 'NewsController@article');

//     /*
//     * Properties routes -- Note: always put the new routes above this property routes...
//     */
//     Route::post('/search', ['as' => 'property.search', 'uses'=>'PropertiesController@search']);

//     // This is a route to obtain data via AJAX:
//     Route::post('/properties/get_locationList', 'PropertiesController@get_locationList');
//     Route::get('/property/{property}/{propertyId}', 'PropertiesController@property');

//     Route::get('/{property_type}-for-{type}/{any?}', 'PropertiesController@index')->where('property_type', '.*')->where('any', '.*');
//     Route::get('/{property_type}-for-{type}/', 'PropertiesController@index')->where('property_type', '.*');
//     //Route::get('/property-for-sale/', 'PropertiesController@index')->name('search-sale');

//     Route::get('/{property_type}-to-{type}/{any}', 'PropertiesController@index')->where('property_type', '.*')->where('any', '.*');
//     Route::get('/{property_type}-to-{type}/', 'PropertiesController@index')->where('property_type', '.*');

//     //Route::get('/property-for-rent/{any}', 'PropertiesController@index')->where('any', '.*');
//     //Route::get('/property-for-rent/', 'PropertiesController@index')->name('search-rent');


//     // Temporary route:
//     Route::get('/properties', 'PropertiesController@index');

//     /*
//     * Drawmap routes
//     */
//     Route::group( ['prefix' => 'drawmap'], function(){
//         Route::get('/', 'DrawmapController@index');
//         Route::get('/ajax/get_properties/', 'DrawmapController@get_properties');
//     });
// });

