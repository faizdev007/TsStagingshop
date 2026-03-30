<?php
    /*--------------------------
    * Backend BreadCrumbs...
    --------------------------*/
    //Home
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('admin.index', function ($trail) {
    $trail->push('Home', route('admin.index'));
});

//Properties
Breadcrumbs::for('properties.index', function ($trail) {

    $trail->parent('admin.index');

    $segment3 = request()->segment(3);

    if(request('search') == 'yes'){
        Session::put('admin_search', url()->full() );
    }elseif ( $segment3 == '' ) {
        Session::put('admin_search', '' );
    }
    $admin_search = Session::get('admin_search');

    if( !empty($admin_search) ){
        $trail->push('Properties',  $admin_search );
    }else{
        $trail->push('Properties', route('properties.index'));
    }

});

//Properties create
Breadcrumbs::for('properties.create', function ($trail) {
    $trail->parent('properties.index');
    $trail->push('Create', route('properties.create'));
});

//Properties edit
Breadcrumbs::for('properties.edit', function ($trail, $property) {
    $trail->parent('properties.index');
    $trail->push('Edit', route('properties.edit', $property));
});

//Properties location
Breadcrumbs::for('properties.location', function ($trail, $property) {
    $trail->parent('properties.index');
    $trail->push('Location', route('properties.location', $property));
});

//Properties photos
Breadcrumbs::for('properties.photos', function ($trail, $property) {
    $trail->parent('properties.index');
    $trail->push('Photos', route('properties.photos', $property));
});

//Properties floorplans
Breadcrumbs::for('properties.floorplans', function ($trail, $property) {
    $trail->parent('properties.index');
    $trail->push('Floorplans', route('properties.floorplans', $property));
});

//Properties documents
Breadcrumbs::for('properties.documents', function ($trail, $property) {
    $trail->parent('properties.index');
    $trail->push('Documents', route('properties.documents', $property));
});

//Properties notes
Breadcrumbs::for('properties.notes', function ($trail, $property) {
    $trail->parent('properties.index');
    $trail->push('Notes', route('properties.notes', $property));
});

//Properties leads
Breadcrumbs::for('properties.leads', function ($trail, $property) {
    $trail->parent('properties.index');
    $trail->push('Leads', route('properties.leads', $property));
});

Breadcrumbs::for('properties.response', function ($trail, $property, $lead_id) {
    $trail->parent('properties.leads', $property);
    $trail->push('Reply', route('properties.response', [$property, $lead_id] ));
});


//Users
Breadcrumbs::for('users.index', function ($trail) {
    $trail->parent('admin.index');
    $trail->push('Users', route('users.index'));
});

//User create
Breadcrumbs::for('users.create', function ($trail) {
    $trail->parent('users.index');
    $trail->push('Create User', route('users.create'));
});

//User details
Breadcrumbs::for('users.edit', function ($trail, $user) {
    if(Auth::user()->role == 'super'){
        $trail->parent('users.index');
        $trail->push('Details', route('users.edit', $user));
    }else{
        $trail->parent('admin.index');
        $trail->push('User', route('users.edit', $user));
    }
});

//User Photo
Breadcrumbs::for('users.photo', function ($trail, $user) {
    $trail->parent('users.index');
    $trail->push('Photo', route('users.photo', $user));
});

//Subscribers
Breadcrumbs::for('subscribers.index', function ($trail) {
    $trail->parent('admin.index');
    $trail->push('Subscribers', route('subscribers.index'));
});

//Enquiries
Breadcrumbs::for('enquiries.index', function ($trail) {
    $trail->parent('admin.index');
    $trail->push('Enquiries', route('enquiries.index'));
});

Breadcrumbs::for('enquiries.edit', function ($trail, $lead) {
    $trail->parent('enquiries.index');
    $trail->push('Reply', route('enquiries.edit', $lead));
});

//Slides
Breadcrumbs::for('slides.index', function ($trail) {
    $trail->parent('admin.index');
    $trail->push('Slides', route('slides.index'));
});
Breadcrumbs::for('slides.edit', function ($trail,$slide) {
    $trail->parent('slides.index');
    $trail->push('Edit', route('slides.edit',$slide));
});
Breadcrumbs::for('slides.create', function ($trail) {
    $trail->parent('slides.index');
    $trail->push('Create', route('slides.create'));
});

//Testimonials
Breadcrumbs::for('testimonials.index', function ($trail) {
    $trail->parent('admin.index');
    $trail->push('Testimonials', route('testimonials.index'));
});
Breadcrumbs::for('testimonials.edit', function ($trail,$data) {
    $trail->parent('testimonials.index');
    $trail->push('Edit', route('testimonials.edit',$data));
});
Breadcrumbs::for('testimonials.create', function ($trail) {
    $trail->parent('testimonials.index');
    $trail->push('Create', route('testimonials.create'));
});


//Pages
Breadcrumbs::for('pages.index', function ($trail) {
    $trail->parent('admin.index');
    $trail->push('Pages', route('pages.index'));
});

Breadcrumbs::for('pages.edit', function ($trail, $data) {
    $trail->parent('pages.index');
    $trail->push('Edit', route('pages.edit', $data));
});


// Bespoke Pages..
Breadcrumbs::for('bespoke-pages.index', function($trail)
{
    $trail->parent('admin.index');
    $trail->push('Bespoke', route('bespoke-pages.index'));
});

Breadcrumbs::for('bespoke-pages.edit', function ($trail, $id) {

    $trail->parent('bespoke-pages.index');
    $trail->push('Edit', route('bespoke-pages.edit',$id));
});

Breadcrumbs::for('pages.create', function ($trail) {
    $trail->parent('bespoke-pages.index');
    $trail->push('Create', route('pages.create'));
});

Breadcrumbs::for('bespoke-section.edit', function ($trail,$id) {
    $section = DB::table('page_sections')->where('id', $id)->first();
    $trail->parent('bespoke-pages.edit',$section->page_id);
    $trail->push('Section', route('bespoke-section.edit',$id));
});

//News
Breadcrumbs::for('news.index', function ($trail) {
    $trail->parent('admin.index');
    $trail->push('News', route('news.index'));
});
Breadcrumbs::for('news.edit', function ($trail,$data) {
    $trail->parent('news.index');
    $trail->push('Edit', route('news.edit',$data));
});
Breadcrumbs::for('news.create', function ($trail) {
    $trail->parent('news.index');
    $trail->push('Create', route('news.create'));
});

//Alerts
Breadcrumbs::for('property-alerts.index', function ($trail) {
    $trail->parent('admin.index');
    $trail->push('Property Alerts', route('property-alerts.index'));
});
Breadcrumbs::for('property-alerts.edit', function ($trail,$data) {
    $trail->parent('property-alerts.index');
    $trail->push('Edit', route('property-alerts.edit',$data));
});

// Search Content
Breadcrumbs::for('search-content.index', function ($trail) {
    $trail->parent('admin.index');
    $trail->push('Search SEO Content', route('search-content.index'));
});

Breadcrumbs::for('search-content.create', function ($trail) {
    $trail->parent('search-content.index');
    $trail->push('Create', route('search-content.create'));
});

Breadcrumbs::for('search-content.edit', function ($trail, $data) {
    $trail->parent('search-content.index');
    $trail->push('Edit', route('search-content.edit', $data));
});

// Footer Blocks...
Breadcrumbs::for('footer-blocks.index', function ($trail) {
    $trail->parent('admin.index');
    $trail->push('Footer Blocks', route('footer-blocks.index'));
});

Breadcrumbs::for('footer-blocks.create', function ($trail) {
    $trail->parent('footer-blocks.index');
    $trail->push('Create', route('footer-blocks.create'));
});

Breadcrumbs::for('footer-blocks.edit', function ($trail, $data) {
    $trail->parent('footer-blocks.index');
    $trail->push('Edit', route('footer-blocks.edit', $data));
});

// Branches...
Breadcrumbs::for('branches.index', function ($trail) {
    $trail->parent('admin.index');
    $trail->push('Branches', route('branches.index'));
});

Breadcrumbs::for('branches.create', function ($trail) {
    $trail->parent('branches.index');
    $trail->push('Create', route('branches.create'));
});

Breadcrumbs::for('branches.edit', function ($trail, $data) {
    $trail->parent('branches.index');
    $trail->push('Edit', route('branches.edit', $data));
});

Breadcrumbs::for('market-valuation.index', function( $trail )
{
    $trail->parent('admin.index');
    $trail->push('Market Valuations', route('market-valuation.index'));
});

Breadcrumbs::for('market-valuation.create', function ($trail) {
    $trail->parent('market-valuation.index');
    $trail->push('Create', route('market-valuation.create'));
});

Breadcrumbs::for('market-valuation.edit', function ($trail, $data) {
    $trail->parent('market-valuation.index');
    $trail->push('Edit', route('market-valuation.edit', $data));
});

Breadcrumbs::for('market-valuation.create-note', function ($trail) {
    $trail->parent('market-valuation.index');
    $trail->push('Create Note', route('market-valuation.create-note'));
});



?>
