<?php

use App\Http\Controllers\Frontend\Auth\UserForgotPasswordController;
use App\Http\Controllers\Frontend\Auth\UserLoginController;
use App\Http\Controllers\Frontend\Auth\UserRegisterController;
use App\Http\Controllers\Frontend\Auth\UserResetPasswordController;
use App\Http\Controllers\Frontend\ClientValuationsController;
use App\Http\Controllers\Frontend\DevelopmentsController;
use App\Http\Controllers\Frontend\DrawmapController;
use App\Http\Controllers\Frontend\EnquiriesController;
use App\Http\Controllers\Frontend\FacebookCatalogExportController;
use App\Http\Controllers\Frontend\FeedsController;
use App\Http\Controllers\Frontend\JsonGuidesController;
use App\Http\Controllers\Frontend\LeadsController;
use App\Http\Controllers\Frontend\MembersController;
use App\Http\Controllers\Frontend\MessagesController;
use App\Http\Controllers\Frontend\NewsController;
use App\Http\Controllers\Frontend\NotesController;
use App\Http\Controllers\Frontend\PagesController;
use App\Http\Controllers\Frontend\PropertiesController;
use App\Http\Controllers\Frontend\PropertyAlertController;
use App\Http\Controllers\Frontend\PropertyInquiryController;
use App\Http\Controllers\Frontend\PropertyPdfController;
use App\Http\Controllers\Frontend\RunCmdController;
use App\Http\Controllers\Frontend\ShortlistsController;
use App\Http\Controllers\Frontend\SitemapsController;
use App\Http\Controllers\Frontend\SocialMediaController;
use App\Http\Controllers\Frontend\SubscribersController;
use App\Http\Controllers\Frontend\TestFiltersController;
use App\Http\Controllers\Frontend\UsersController;
use Illuminate\Support\Facades\Route;
use App\Models\Property;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
* Pages routes
*/


Route::get('/runJobs',function(){
    $jobsCount = DB::table('jobs')->count();
    $processed = 0;
    
    if ($jobsCount == 0) return;
    
    while ($processed < $jobsCount) {
    
    
        Artisan::call('queue:work', [
            '--once' => true,
            '--tries' => 3,
            '--timeout' => 90,
        ]);
    
        $processed++;
    }
});


Route::get('/convertjpgtowebp',function(){
    $filepath = DB::table('slides')->limit(100)->get();
    $count = 0;
    
    foreach ($filepath as $single) {
        
        // Only convert png, jpg, jpeg
        // if (!in_array($single->extension, ['png', 'jpg', 'jpeg'])) {
        //     continue;
        // }

        $imagePath = storage_path('app/public/'.$single->photo);

        $webpPath = convertToWebp($imagePath);

        if ($webpPath) {

            $webpRelative = preg_replace('/\.(png|jpg|jpeg)$/i', '.webp', $single->photo);

            DB::table('slides')
                ->where('id', $single->id)
                ->update([
                    'photo' => $webpRelative
                ]);

            $count++;
        }
    }

    return "Converted ".$count." successfully";
});

Route::post('/clear-session', function () {
    session()->forget('success');
    return response()->json(['status' => 'cleared']);
});

Route::get('/update-currency', function () {
    Artisan::call('currency:latest');
    return response()->json(['message' => 'Currency rates updated successfully']);
});

Route::controller(PagesController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/about-us', 'view');
    Route::get('/contact-us', 'contact');
    Route::get('/privacy-policy', 'view');
    Route::get('/disclaimer', 'view');
    Route::get('/terms', 'view');
    Route::get('/valuation', 'valuation');
    Route::get('/testimonials', 'testimonials');
    Route::get('/sell', 'view');
    Route::get('/unsubscribe', 'view');

    Route::get('/profile/{slug}', 'agent_profile')->name('profile');
    Route::post('/profile/{slug}', 'agent_profile')->name('sortprofile');
});

/*
* News routes
*/

Route::controller(NewsController::class)->group(function () {
    Route::get('/blog', 'index');
    Route::get('/article/{slug}', 'article');
    Route::post('/blog/search-result', 'search');
    Route::get('/blog/search-result', 'search');
});

Route::controller(PropertiesController::class)->group(function () {
    Route::post('/properties/get_locationList', 'get_locationList');
    Route::post('/properties/get_countryList', 'get_countryList');
    Route::post('/properties/get_areaList', 'get_areaList');
    Route::post('/properties/get_complexList', 'get_complexList');
    Route::get('/properties/set/currency/{currency}','set_currency');
    Route::post('/properties/ajax-search-map', 'ajax_search_map');
    
    Route::get('/property/{property}/{propertyId}', 'property');
    Route::get('/property/{property}/{propertyId}/{currencytype}', 'propertysearchprice');
    Route::get('/property-pdf/{property}/{propertyId}', 'property_pdf');
    Route::get('/pdf/{propertyId}', 'generate_pdf');
    Route::get('/property-primary-photo/{propertyId}', 'propertyPrimaryPhoto');
    
    Route::post('/calculate-mortgage', 'calculate_mortgage');
    Route::post('/stamp-duty-calculator', 'stamp_duty_calculator');
    
    Route::get('/price-ranges/{type}', 'get_prices_type');
    
    
    Route::get('/properties/{category}', 'by_category');
    Route::get('/properties/{category}/sort/{any}', 'by_category');
    
    Route::get('/{property_type}-for-{type}/{any?}', 'index')->where('property_type', '.*')->where('any', '.*');
    Route::get('/{property_type}-for-{type}/', 'index')->where('property_type', '.*');
    //Route::get('/property-for-sale/', 'index')->name('search-sale');
    
    Route::get('/{property_type}-to-{type}/{any}', 'index')->where('property_type', '.*')->where('any', '.*');
    Route::get('/{property_type}-to-{type}/', 'index')->where('property_type', '.*');
    
    
    Route::get('/property-all/{any}', 'index')->where('property_type', '.*')->where('any', '.*');
    Route::get('/property-all/', 'index')->where('property_type', '.*');

    Route::get('/search-map', 'search_map');
    Route::post('/map-search', 'search');

    Route::post('/search', 'search')->name('property.search');

    Route::post('/save-search', 'save_search');
    Route::delete('/save-search/{id}', 'delete_search');


    Route::get('/properties/get_countryLists', 'get_countryLists');
    Route::get('/properties/get_areaLists', 'get_areaLists');
    Route::get('/properties/get_complexLists', 'get_complexLists');
});

/*--------------------------
| Utilities
--------------------------*/
Route::get('/runcmd', [RunCmdController::class, 'index']);

Route::get('/json/get', [JsonGuidesController::class, 'index']);


/*--------------------------
| Cookie Consent
--------------------------*/
Route::controller(DevelopmentsController::class)->group(function () {

    Route::get('/cookie/accept-essential', 'acceptEssential')
        ->name('cookie.acceptEssential');

    Route::get('/cookie/accept-all', 'acceptAll')
        ->name('cookie.acceptAll');

    Route::post('/cookie/custom', 'custom')
        ->name('cookie.custom');
});



// Test route for 500 error page
// Route::get('/test-500', function () {
//     abort(500, 'Test 500 Error Page');
// });


// Property PDF Download routes
Route::post('/ajax/property-pdf-download/{ref}', [PropertyPdfController::class,'downloadPdf']);
// Dynamic search route
// Route::get('/get-dynamic-search-data', [PropertiesController::class, 'getDynamicSearchData'])
//     ->name('dynamic.search.data');

// Frontend error logging route
// Route::post('/log-frontend-error', [ErrorLogController::class, 'logFrontendError'])
//     ->name('log.frontend.error');

/*---------------------------------------
| Facebook Catalog
----------------------------------------*/
Route::middleware(['super'])->group(function () {
    Route::controller(FacebookCatalogExportController::class)->group(function () {
        Route::get('/facebook-catalog', 'showForm')
            ->name('facebook.catalog.form');

        Route::post('/facebook-catalog/export', 'exportToXML')
            ->name('export.facebook.catalog');
    });
});


/*---------------------------------------
| Shortlist
----------------------------------------*/
Route::prefix('shortlist')->group(function () {

    Route::controller(ShortlistsController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/ajax/add', 'store');
        Route::post('/ajax/total', 'total');
    });

    // Catch-all (keep LAST)
    Route::get('/{any}', [PropertiesController::class, 'index'])
        ->where('any', '.*');
});


/*---------------------------------------
| Drawmap
----------------------------------------*/
Route::controller(DrawmapController::class)
    ->prefix('drawmap')
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/ajax/get_properties', 'get_properties');
    });


/*---------------------------------------
| Property Alerts
----------------------------------------*/
Route::controller(PropertyAlertController::class)
    ->prefix('alert')
    ->group(function () {
        Route::get('/cron', 'cron');
        Route::get('/unsubscribe', 'unsubscribe');
        Route::post('/ajax/add', 'add');
        Route::delete('/{id}', 'destroy');
    });


/*---------------------------------------
| Leads
----------------------------------------*/
Route::controller(LeadsController::class)
    ->prefix('leads')
    ->group(function () {
        Route::get('/cron/send-email-automation', 'sendLeadAutomations');
        Route::get('/unsubscribe', 'unsubscribe');
    });


/*---------------------------------------
| Developments
----------------------------------------*/
Route::get('/new-developments',
    [DevelopmentsController::class, 'index']
);

/*
* Properties routes -- Note: always put the new routes above this property routes...
*/


// Route::post('/search', ['as' => 'filter.search', 'uses'=>'PropertiesController@search']);


// This is a route to obtain data via AJAX:
    


// Temporary route:
// Route::get('/properties', 'PropertiesController@index');

// // Test route for grid filters
// Route::get('/grid-filters-test', 'TestFiltersController@index');

/*--------------------------
| Utilities
--------------------------*/
Route::get('/update_field', [TestFiltersController::class, 'newdevelopment']);


/*--------------------------
| Enquiries (AJAX)
--------------------------*/
Route::controller(EnquiriesController::class)->prefix('ajax')->group(function () {

    Route::post('/contact', 'enquiry');
    Route::post('/valuation', 'valuation');
    Route::post('/bottom_generic', 'bottom_generic');

    Route::post('/property-sidebar-form/{ref}', 'property_sidebar_form')->name('sideformQuery');
    Route::post('/property-bottom-form/{ref}', 'property_bottom_form');
    Route::post('/generic_enquiry', 'generic_enquiry');
    Route::post('/property-slider-form/{ref}', 'property_slider_form');
    Route::post('/request-viewing', 'request_viewing');

    Route::post('/home-cta-1', 'home_cta_1');
    Route::post('/home-cta-2', 'home_cta_2');
    Route::post('/home-cta-3', 'home_cta_3');

    Route::post('/insertClick', 'insertClick')->name('insertClick');
});


/*--------------------------
| Property Inquiry (AJAX)
--------------------------*/
Route::controller(PropertyInquiryController::class)->prefix('ajax')->group(function () {

    Route::post('/property-inquiry-form', 'submitInquiry')
        ->name('property.inquiry.submit');

    Route::post('/get-areas', 'getAreasByCountry');
    Route::post('/getprojects', 'getProjectByArea');
});


/*--------------------------
| Click Test Page
--------------------------*/


/*--------------------------
| Sitemap
--------------------------*/
Route::redirect('/sitemap.xml', 'sitemap_index.xml', 301);

Route::controller(SitemapsController::class)->group(function () {
    Route::get('/sitemap_index.xml', 'index')->name('sitemap.index');
    Route::get('/sitemap-pages.xml', 'pages')->name('sitemap.pages');
    Route::get('/sitemap-news.xml', 'news')->name('sitemap.news');
    Route::get('/sitemap-properties.xml', 'properties')->name('sitemap.properties');
    Route::get('/sitemap-searches.xml', 'searches')->name('sitemap.searches');
});


/*--------------------------
| Subscriber (AJAX)
--------------------------*/
Route::post('/ajax/subscribe',
    [SubscribersController::class, 'subscribe']
);

/* Save Searches (Members Area Only)...
---------------------------------------- */

Route::group(['middleware' => ['members']], function ()
{
    /* Login / Register (Members Area)...
---------------------------------------- */
Route::controller(UserLoginController::class)->group(function(){
    Route::get('/login', 'showLoginForm');
    Route::post('/login', 'userLogin');
    Route::post('/ajax-login', 'customLogin');
    Route::get('/logout', 'logout');
    Route::delete('/member/{id}', 'destroy');
});

Route::controller(UserRegisterController::class)->group(function(){
    Route::get('/register', 'showRegistrationForm');
    Route::post('/register', 'register');
    Route::post('/ajax-register', 'customRegister');
});

    /*---------------------------------------
    | Members Area
    ----------------------------------------*/
    Route::prefix('account')->group(function () {

        Route::get('/', [UsersController::class, 'index']);

        Route::controller(MembersController::class)->group(function () {
            Route::get('/shortlist', 'shortlist');
            Route::get('/saved-searches', 'saved_search');
            Route::get('/alert-conversion/{id}', 'convert_to_alert');
            Route::get('/property-alerts', 'property_alerts');
        });

        Route::resource('/notes', NotesController::class);
        Route::resource('/messages', MessagesController::class);

        Route::get('/get-user-notes/{id}',
            [NotesController::class, 'getUserPropertyNotes']
        );
    });

    /*---------------------------------------
    | Outside Account Prefix
    ----------------------------------------*/
    Route::delete('/note/{id}',
        [NotesController::class, 'destroy']
    );

    Route::post('/property-alert/{id}/edit',
        [PropertyAlertController::class, 'update']
    );

});


    /* Forgotten / Reset Password Links (Members).....
    ------------------------------------------------------ */
    // Password Reset Routes
    /*---------------------------------------
    | Member Password Reset (Auth)
    ----------------------------------------*/
    Route::controller(UserForgotPasswordController::class)->group(function () {
        Route::post('/password/email', 'sendResetLinkEmail')
            ->name('member.password.email');

        Route::get('/password/reset', 'showLinkRequestForm')
            ->name('member.password.request');
    });

    Route::controller(UserResetPasswordController::class)->group(function () {
        Route::post('/password/reset', 'reset')
            ->name('member.password.set');

        Route::get('/password/reset/{token}', 'showResetForm')
            ->name('member.password.reset');

        Route::post('/check-email-exists', 'checkEmailExists')
            ->name('check.email.exists')
            ->middleware('throttle:6,1');
    });


    /*---------------------------------------
    | Members Area
    ----------------------------------------*/
    Route::middleware(['members'])->group(function () {
        Route::get('/account', [UsersController::class, 'index']);
        Route::post('/user/{id}/update', [UsersController::class, 'update']);
    });


    /*---------------------------------------
    | Social Media
    ----------------------------------------*/
    Route::controller(SocialMediaController::class)
        ->prefix('social-media')
        ->group(function () {
            Route::post('/facebook-webhook', 'facebook_webhook');
        });


    /*---------------------------------------
    | Feeds
    ----------------------------------------*/
    Route::controller(FeedsController::class)
        ->prefix('feeds')
        ->group(function () {
            Route::get('/kyero', 'kyero');
            Route::get('/facebook', 'facebook');
            Route::get('/brown-harris-stevens', 'brown_harris_feed')
                ->name('bhs_feed');
    });



/*--------------------------
| Valuation Reports
--------------------------*/
Route::controller(ClientValuationsController::class)
->prefix('valuation-report')
->group(function () {

    Route::get('/{id}', 'show');
    Route::post('/accept', 'accept');
});

// Route::get('settings', function () {
//     return Redirect::to('', 301);
// });


// Route::get('/testingpage', function () {
//     $property = \App\Property::where('ref','S-1006')->first();
//     return view('testingpage', compact('property'));
// });


