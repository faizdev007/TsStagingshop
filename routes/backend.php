<?php

use App\Http\Controllers\Backend\Auth\ForgotPasswordController;
use App\Http\Controllers\Backend\Auth\LoginController;
use App\Http\Controllers\Backend\Auth\RegisterController;
use App\Http\Controllers\Backend\Auth\ResetPasswordController;
use App\Http\Controllers\Backend\BespokePagesController;
use App\Http\Controllers\Backend\BranchesController;
use App\Http\Controllers\Backend\ClientValuationsController;
use App\Http\Controllers\Backend\CommunitiesController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\DevelopmentsController;
use App\Http\Controllers\Backend\EnquiriesController;
use App\Http\Controllers\Backend\FooterBlockController;
use App\Http\Controllers\Backend\GoogleIndexingController;
use App\Http\Controllers\Backend\LanguagesController;
use App\Http\Controllers\Backend\LeadsController;
use App\Http\Controllers\Backend\LoginHistoryController;
use App\Http\Controllers\Backend\MembersController;
use App\Http\Controllers\Backend\MetadataController;
use App\Http\Controllers\Backend\NewsCategoryController;
use App\Http\Controllers\Backend\NewsController;
use App\Http\Controllers\Backend\PagesController;
use App\Http\Controllers\Backend\PropertiesController;
use App\Http\Controllers\Backend\PropertyAlertController;
use App\Http\Controllers\Backend\PropertyTypesController;
use App\Http\Controllers\Backend\PropertyviewController;
use App\Http\Controllers\Backend\SearchContentController;
use App\Http\Controllers\Backend\SlidesController;
use App\Http\Controllers\Backend\SocialMediaController;
use App\Http\Controllers\Backend\SubscribersController;
use App\Http\Controllers\Backend\TeamController;
use App\Http\Controllers\Backend\TestimonialsController;
use App\Http\Controllers\Backend\SitemapHidesController;
use App\Http\Controllers\Backend\UsersController;
use App\Http\Controllers\Backend\WhatsappController;
use App\Http\Controllers\Frontend\PagesController as FrontendPagesController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;

/*
|--------------------------------------------------------------------------
| Backend Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great
|
*/

Route::post('/clear-session', function () {
    session()->forget('success');
    return response()->json(['status' => 'cleared']);
});


Route::group( ['prefix' => 'admin'], function()
{
    /*--------------------------
    * Authentication Routes...
    --------------------------*/
    Route::controller(LoginController::class)->group(function(){
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');
        Route::post('/login/send-otp', 'sendOTP')->name('login.send.otp');
        Route::post('/login/verify-otp', 'verifyOTP')->name('login.verify.otp');
        Route::post('/logout', 'logout')->name('logout');
    });

    ///- Registration Routes...
    Route::get('/register', [RegisterController::class,'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class,'register']);

    ///- Password Reset Routes...
    Route::get('/password/reset', [ForgotPasswordController::class,'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [ForgotPasswordController::class,'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [ResetPasswordController::class,'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class,'reset'])->name('password.update');
    //Route::post('/password/reset', [ResetPasswordController::class,'reset']);


    Route::get('login-history', [LoginHistoryController::class,'index'])->name('login-history.index');


    //Dashboard
    Route::get('/', [DashboardController::class,'index'])->name('admin.index');
    //Route::get('/home', 'DashboardController@index');

    // All Agent Or Higher Allowed Routes....
    Route::group(['middleware' => ['agent']], function ()
    {
        /*--------------------------
        * Properties
        --------------------------*/
        Route::resource('/properties',PropertiesController::class);
        Route::controller(PropertiesController::class)->group(function(){
            Route::get('/properties/{property}/location', 'location')->name('properties.location');
            Route::get('/properties/{property}/notes', 'notes')->name('properties.notes');
    
            
            Route::get('properties/{id}/duplicate', 'duplicate')->name('properties.duplicate');
    
    
            /*--------------------------
            | Property Claim Stats
            --------------------------*/
            Route::controller(PropertiesController::class)->group(function () {

                Route::get('properties-claim', 'claimProperties')
                    ->name('properties.claim');

                Route::post('properties-claim', 'saveClaimProperties')
                    ->name('properties.claim.save');

                Route::get('properties-claim/search', 'claimProperties')
                    ->name('properties.claim.search');

                Route::get(
                    'properties/generate-stats-pdf/{id}',
                    'generatePropertyStatsPdf'
                )->name('properties.generate-stats-pdf');

                Route::get('properties/{property}/archive', 'archive')
                    ->name('properties.archive');

                Route::get('properties/{property}/reactive', 'reactive')
                    ->name('properties.reactive');

                Route::get('properties/{property}/delete', 'delete')
                    ->name('properties.delete');

                Route::post('properties/get/locations', 'get_all_locations')
                    ->name('properties.get.locations');

                Route::match(['put', 'patch'],
                    'properties/{property}/location-update',
                    'locationUpdate'
                )->name('properties.location.update');

                Route::match(['put', 'patch'],
                    'properties/{property}/agentnote-update',
                    'agentnoteUpdate'
                )->name('properties.agentnote.update');
            });

            /*--------------------------
            * Property Photos
            --------------------------*/
            Route::get('/properties/{property}/photos','photos')->name('properties.photos');
            Route::post('/properties/{property}/photo-upload', 'photo_upload')->name('properties.photo-upload.save');
            Route::post('/properties/{property}/photo-sort','photoSort')->name('properties.photo.sort');
            Route::post('/properties/{property}/photo-caption','photoCaption')->name('properties.photo.caption');
            Route::delete('/properties/{property}/photos','photoDestroy')->name('properties.photo.destroy');

            /*--------------------------
            * Property Floorplans
            --------------------------*/
            Route::get('/properties/{property}/floorplans','floorplans')->name('properties.floorplans');
            Route::post('/properties/{property}/floorplan-upload','floorplan_upload')->name('properties.floorplan-upload.save');
            Route::post('/properties/{property}/floorplan-sort', 'floorplanSort')->name('properties.floorplan.sort');
            Route::post('/properties/{property}/floorplan-caption','floorplanCaption')->name('properties.floorplan.caption');
            Route::delete('/properties/{property}/floorplans','floorplanDestroy')->name('properties.floorplan.destroy');

            Route::prefix('properties/{property}')
            ->group(function () {
                /*--------------------------
                * Property Documents
                --------------------------*/
                Route::get('documents', 'documents')->name('properties.documents');
                Route::post('document-upload', 'document_upload')->name('properties.document-upload.save');
                Route::post('document-sort', 'documentSort')->name('properties.document.sort');
                Route::post('document-caption', 'documentCaption')->name('properties.document.caption');
                Route::delete('documents', 'documentDestroy')->name('properties.document.destroy');

                Route::get('leads', 'leads')->name('properties.leads');
                Route::get('response/{id}', 'response')->name('properties.response');

                /*--------------------------
                * Property Stats
                --------------------------*/
                Route::get('stats', 'stats')->name('properties.stats');
                /*--------------------------
                * Property history
                --------------------------*/
                Route::get('history', 'history')->name('properties.history');
            });
        });


        /*--------------------------
        | Developments / Units
        --------------------------*/
        Route::controller(DevelopmentsController::class)->group(function () {

            Route::get('/developments', 'index')
                ->name('developments.index');

            Route::prefix('properties/{property}')->group(function () {
                Route::get('units', 'units')
                    ->name('developments.units');

                Route::get('create-unit', 'create_unit')
                    ->name('developments.units.create');

                Route::post('create-unit', 'store_unit')
                    ->name('developments.units.store');
            });

            Route::get('development-unit/{id}/edit', 'edit_unit')
                ->name('developments.unit.edit');

            Route::get('development-unit/{id}/duplicate', 'duplicate_unit')
                ->name('developments.unit.duplicate');

            Route::post('development-unit/{id}/edit', 'update_unit')
                ->name('developments.unit.update');

            Route::post('development-units/sort', 'sort_units')
                ->name('developments.units.sort');

            Route::delete('development-unit/{id}', 'destroy_unit')
                ->name('developments.unit.destroy');

            Route::post('development-unit/{id}/photo-upload', 'photo_upload')
                ->name('developments.photo-upload.save');

            Route::post('development-unit/{id}/photo-sort', 'photo_sort')
                ->name('developments.photo.sort');

            Route::delete('development-unit/{id}/delete-photo', 'delete_photo')
                ->name('developments.photo.delete');
        });


        /*--------------------------
        | Properties
        --------------------------*/
        Route::resource('properties', PropertiesController::class);
        /*
        Auto names:
        properties.index
        properties.create
        properties.store
        properties.show
        properties.edit
        properties.update
        properties.destroy
        */


        /*--------------------------
        | Property Types
        --------------------------*/
        Route::controller(PropertyTypesController::class)
            ->prefix('property-types')
            ->group(function () {

                Route::get('/', 'index')
                    ->name('property-types.index');

                Route::get('create', 'create')
                    ->name('property-types.create');

                Route::post('/', 'store')
                    ->name('property-types.store');

                Route::get('{id}/edit', 'edit')
                    ->name('property-types.edit');

                Route::put('{id}', 'update')
                    ->name('property-types.update');

                Route::delete('{id}', 'destroy')
                    ->name('property-types.destroy');
            });


        /*--------------------------
        | Enquiries
        --------------------------*/
        Route::controller(EnquiriesController::class)
            ->prefix('enquiries')
            ->group(function () {

                Route::get('/', 'index')
                    ->name('enquiries.index');

                Route::get('{id}/edit', 'edit')
                    ->name('enquiries.edit');

                Route::put('{id}', 'update')
                    ->name('enquiries.update');

                Route::patch('{id}', 'update');

                Route::delete('{id}', 'destroy')
                    ->name('enquiries.destroy');

                Route::get('{id}/delete', 'delete')
                    ->name('enquiries.delete');

                Route::get('{id}/download', 'download')
                    ->name('enquiries.download');

                Route::match(['put', 'patch'], '{enquiry}/addnote-update', 'addnoteUpdate')
                    ->name('enquiries.addnote-update');

                Route::post('selection-note-update', 'selectionNoteUpdate')
                    ->name('enquiries.selection-note-update');
            });


        /*--------------------------
        | Extra Property / Ajax Routes
        --------------------------*/
        Route::get('/properties/get/locations',
            [PropertiesController::class, 'get_all_locations']
        )->name('properties.get.locations');

        Route::get('/data/get/email-clicks/{id}',
            [LeadsController::class, 'get_email_clicks']
        )->name('leads.email-clicks');

        Route::post('/properties/addnewstatus',
            [PropertiesController::class, 'addnewstatus']
        )->name('properties.addnewstatus');

    });

    /*--------------------------
    Whatsapp
    --------------------------*/
    Route::get('/whatsapp', [WhatsappController::class,'redirectToView']);

    // Route::get('/propertytypes', 'PropertyTypesController@redirectToView');


        /*--------------------------
    Property View
    --------------------------*/
    Route::get('/propertyviews', [PropertyviewController::class,'redirectToView']);
        



        

    Route::middleware(['admin'])->group(function () {

        /*--------------------------
        | Users
        --------------------------*/
        Route::resource('users', UsersController::class);

        Route::controller(UsersController::class)->prefix('users')->group(function () {
            Route::get('{user}/photo', 'photo')->name('users.photo');
            Route::post('{user}/photo-upload', 'photoUpload')->name('user.photo-upload.save');
            Route::get('{user}/delete-photo', 'deletePhoto');
            Route::post('get/agents', 'get_all_agents');
            Route::get('{user}/delete', 'delete');
        });

        Route::post('users/get/filtercommunities',
            [CommunitiesController::class, 'get_all_communities']
        );

        /*--------------------------
        | Subscribers
        --------------------------*/
        Route::resource('subscribers', SubscribersController::class);

        Route::controller(SubscribersController::class)->prefix('subscribers')->group(function () {
            Route::get('{subscriber}/delete', 'delete');
            Route::get('{subscriber}/download', 'download');
        });

        /*--------------------------
        | Slides
        --------------------------*/
        Route::resource('slides', SlidesController::class);

        Route::controller(SlidesController::class)->prefix('slides')->group(function () {
            Route::match(['get','post'], '{slide}/upload', 'upload');
            Route::get('{slide}/delete', 'delete');
            Route::get('{slide}/delete_photo', 'delete_photo');
            Route::post('sort', 'sort');
        });

        /*--------------------------
        | Communities
        --------------------------*/
        Route::resource('communities', CommunitiesController::class);

        Route::controller(CommunitiesController::class)->prefix('communities')->group(function () {
            Route::post('{id}/photo-upload', 'upload')->name('communities.photo-upload.save');
            Route::get('{id}/delete', 'delete');
            Route::get('{id}/delete_photo', 'delete_photo');
            Route::post('sequence', 'sequence');
        });

        /*--------------------------
        | Post Categories
        --------------------------*/
        Route::resource('postcategories', NewsCategoryController::class);
        Route::get('postcategories/{id}/delete',
            [NewsCategoryController::class, 'delete']
        );

        /*--------------------------
        | Testimonials
        --------------------------*/
        Route::resource('testimonials', TestimonialsController::class);

        Route::controller(TestimonialsController::class)->prefix('testimonials')->group(function () {
            Route::get('{testimonial}/delete', 'delete');
            Route::post('sort', 'sort');
        });

        /*--------------------------
        | Team
        --------------------------*/
        Route::resource('team', TeamController::class);

        Route::controller(TeamController::class)->prefix('team')->group(function () {
            Route::post('{id}/upload', 'upload');
            Route::post('sort', 'sort');
            Route::get('{id}/delete-photo', 'delete_photo');
        });

        /*--------------------------
        | Metadata
        --------------------------*/
        Route::resource('metadata', MetadataController::class);
        Route::get('metadata/{id}/delete',
            [MetadataController::class, 'delete']
        );

        /*--------------------------
        | Pages
        --------------------------*/
        Route::resource('pages', PagesController::class);

        Route::controller(PagesController::class)->prefix('pages')->group(function () {
            Route::match(['get','post'], '{page}/upload', 'upload')->name('pages.upload');
            Route::post('{page}/update_meta', 'update_meta')->name('pages.update_meta');
            Route::get('{page}/delete_photo', 'delete_photo')->name('pages.delete_photo');
            Route::post('generate-slug', 'generate_slug')->name('pages.generate_slug');
        });

        /*--------------------------
        | Bespoke Pages
        --------------------------*/
        Route::resource('bespoke-pages', BespokePagesController::class);

        Route::controller(BespokePagesController::class)->group(function () {
            Route::post('create-section', 'create_section');
            Route::get('bespoke-section/{id}/edit', 'edit_section')->name('bespoke-section.edit');
            Route::get('bespoke-section-content/{id}/edit', 'update_section_content');
            Route::post('page-sections/sort', 'section_sort');
            Route::post('bespoke-section/{id}/edit', 'update_section');
            Route::post('bespoke-section-content/{id}/edit', 'store_section_content');
            Route::delete('bespoke-section/{id}', 'destroy_section');
            Route::delete('section-content/{id}', 'destroy_content');
            Route::post('things-to-do/{id}/upload', 'add_image');
            Route::post('page-content/sort/{id}', 'content_sort');
            Route::delete('things-to-do/{id}', 'delete_photo');
        });

        /*--------------------------
        | News
        --------------------------*/
        Route::resource('news', NewsController::class);

        Route::controller(NewsController::class)->prefix('news')->group(function () {
            Route::get('{article}/delete', 'delete')->name('delete');
            Route::post('{article}/upload', 'upload')->name('upload');
            Route::get('{article}/delete_photo', 'delete_photo')->name('news.delete_photo');
            Route::post('{article}/update_meta', 'update_meta')->name('news.update_meta');
            Route::get('{article}/restore', 'restore')->name('restore');
        });

        /*--------------------------
        | Sitemap Hides
        --------------------------*/
        Route::resource('sitemap_hides', SitemapHidesController::class);

        /*--------------------------
        | Leads Automation
        --------------------------*/
        Route::controller(LeadsController::class)->group(function () {
            Route::get('enquiries/set-automation/{id}/{type}', 'create_automation');
            Route::get('enquiries/remove-automation/{id}', 'cancel_automation');
        });

        /*--------------------------
        | Property Alerts
        --------------------------*/
        Route::resource('property-alerts', PropertyAlertController::class);
        Route::get('property-alerts/{id}/delete',
            [PropertyAlertController::class, 'delete']
        );

        /*--------------------------
        | Branches
        --------------------------*/
        Route::resource('branches', BranchesController::class);

        /*--------------------------
        | Market Valuation
        --------------------------*/
        Route::controller(ClientValuationsController::class)->group(function () {
            Route::get('market-valuation/note/{id}', 'create_note')
                ->name('market-valuation.create-note');
            Route::post('market-valuation/note/{id}', 'save_note');
            Route::post('market-valuation-email', 'send_email');
            Route::get('market-valuation/create-property/{id}', 'create_property_record');

            Route::get('market-valuation/why-list', 'list_index');
            Route::get('market-valuation/why-list/create', 'create_list_item');
            Route::post('market-valuation/why-list/create', 'store_list_item');
            Route::get('market-valuation/why-list/edit/{id}', 'edit_list_item');
            Route::post('market-valuation/why-list/edit/{id}', 'update_list_item');
            Route::delete('why-list-item/{id}', 'delete_item');
            Route::post('why-list/sort', 'sort_items');

            Route::resource('market-valuation', ClientValuationsController::class);
            Route::get('properties/{id}/create-valuation', 'store_property');
        });

        /*--------------------------
        | Google Indexing
        --------------------------*/
        Route::post(
            'google-indexing/request',
            [GoogleIndexingController::class, 'requestIndexing']
        )->name('admin.google.request-indexing');
    });

    

    /*--------------------------
    | Members Area
    --------------------------*/
    Route::middleware(['members'])->group(function () {

        Route::controller(MembersController::class)->prefix('members')->group(function () {
            Route::get('message/{id?}', 'send_new_message');
            Route::get('messages', 'messages');
            Route::get('messages/{id}', 'show_message');
            Route::post('messages/reply', 'send_reply');
        });

        Route::resource('members', MembersController::class);
    });


    /*--------------------------
    | Superadmin ONLY Routes
    --------------------------*/
    Route::middleware(['super'])->group(function () {

        /*--------------------------
        | Property Approval
        --------------------------*/
        Route::controller(PropertiesController::class)->group(function () {
            Route::get('approve-property/{pid}', 'property_approvel');
            Route::get('approvel_reject_property/{pid}', 'property_reject');

            Route::get('approve-claim-property/{pid}/{uid}', 'property_claim_approvel');
            Route::get('approvel_reject_claim_property/{pid}/{uid}', 'property_claim_reject');
            Route::get('revoke_claim_property/{pid}/{uid}/{provide_date}', 'revoke_claim_property');
        });

        /*--------------------------
        | Search SEO Content
        --------------------------*/
        Route::resource('search-content', SearchContentController::class);

        Route::controller(SearchContentController::class)->group(function () {
            Route::delete('search-content-block/{id}', 'destroy_block');
            Route::get('get-languages', 'get_languages');
        });

        /*--------------------------
        | Social Media
        --------------------------*/
        Route::controller(SocialMediaController::class)
            ->prefix('social-media')
            ->group(function () {
                Route::get('/', 'index');
                Route::get('facebook-callback', 'facebook_callback');
            });

        /*--------------------------
        | Footer Blocks
        --------------------------*/
        Route::resource('footer-blocks', FooterBlockController::class);

        Route::controller(FooterBlockController::class)->group(function () {
            Route::post('footer-blocks/sort', 'blocks_sort');
            Route::post('footer-links/sort', 'links_sort');

            Route::get('footer-blocks/links/create/{id}', 'links_create');
            Route::post('footer-blocks/links/create', 'links_store');
            Route::get('footer-blocks/links/{id}/edit', 'links_edit');
            Route::put('footer-blocks/links/{id}/edit', 'links_update');
            Route::delete('footer-link/{id}', 'links_destroy');
        });

        /*--------------------------
        | Languages
        --------------------------*/
        Route::controller(LanguagesController::class)->group(function () {
            Route::get('settings/languages', 'index');
            Route::post('settings/languages', 'save');
        });

        /*--------------------------
        | Logs
        --------------------------*/
        Route::get('logs', [LogViewerController::class, 'index']);
    });

});

// Last Route As Fallback for Top Level Pages..
Route::get('/{any}', [FrontendPagesController::class,'view'])->where('any', '.*');
