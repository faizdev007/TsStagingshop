<?php

// source: https://github.com/dwightwatson/sitemap

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\SitemapHides;
use App\Property;
use App\PropertyType;
use App\Page;
use App\Post;
use Sitemap;
use Carbon\Carbon;

class SitemapsController extends Controller
{

    public function index()
    {
        // Add Pages sitemap to index
        Sitemap::addSitemap(route('sitemap.pages'));

        // Add paginated News sitemap(s) to index
        // Take the same logic as what's in the news() method below:
        $news = Post::published()->paginate(350);
        // If there's a simpler method to do this loop, let me know! RH
        for ($i = 1; $i <= $news->lastPage(); $i++)
        {
            $route = route('sitemap.news');
            if ($i > 1)
            {
                $route .= '?page='.$i;
            }
            Sitemap::addSitemap($route);
        }

        // Add Specialist Searches sitemap to index
        Sitemap::addSitemap(route('sitemap.searches'));

        // Add paginated Properties sitemap(s) to index
        $property = new Property;
        // If there's a simpler method to do this loop, let me know! RH
        for ($i = 1; $i <= $property->getAllForSitemap()->lastPage(); $i++)
        {
            $route = route('sitemap.properties');
            if ($i > 1)
            {
                $route .= '?page='.$i;
            }
            Sitemap::addSitemap($route);
        }

        return Sitemap::index();
    }

    /**
     * Sitemap for Pages
     */
    public function pages()
    {
        // Pages
        $pages = Page::orderBy('route', 'ASC')
          ->get();

        foreach ($pages as $page)
        {
            $sitemap_url = url($page->route);

            // Check url if exist in sitemap hides
            $hide_url = SitemapHides::where('url', $sitemap_url)->first();

            if(!$hide_url){
                Sitemap::addTag($sitemap_url, $page->updated_at, 'daily', '0.8');
            }
        }

        // I'll add the entry for /blog here because the News sitemap is in a pagination loop
        Sitemap::addTag(url('blog'), Carbon::today(), 'daily', '0.8');

        return Sitemap::render();
    }

    /**
     * Sitemap for News
     */
    public function news()
    {
        // Paginated News
        $news = Post::published()->newest()->paginate(350);

        foreach ($news as $article)
        {
            $sitemap_url = url('article/'.$article->slug);

            // Check url if exist in sitemap hides
            $hide_url = SitemapHides::where('url', $sitemap_url)->first();

            if (!$hide_url)
            {
                Sitemap::addTag($sitemap_url, $article->updated_at, 'daily', '0.8');
            }
        }

        return Sitemap::render();
    }

    /**
     * Sitemap for Searches
     */
    public function searches()
    {

        $property = new Property();

        $propertyTypes = propertyType::orderBy('name')->get();

        foreach($propertyTypes as $propertyType){
            $propertyTypesArray[]=$propertyType->slug;
        }

        //With type and tenure
        foreach($propertyTypesArray as $propertyType)
        {
            $tenures = p_fieldTypes_no_default();

            foreach( $tenures as $tenure)
            {
                $tenure_url = ($tenure == 'Sale') ? '-for-'.strtolower($tenure) : '-to-'.strtolower($tenure);
                $sitemap_url = url($propertyType.$tenure_url);

                // Check url if exist in sitemap hides
                $hide_url = SitemapHides::where('url', $sitemap_url)->first();

                if(!$hide_url){
                    Sitemap::addTag(url('property'.$tenure_url.'/property-type/'.$propertyType), '', 'daily', '0.8');
                }

            }
        }

        //With type, tenure and location
        foreach($propertyTypesArray as $propertyType)
        {
            $tenures = p_fieldTypes_no_default();
            foreach( $tenures as $tenure)
            {
                $locationList = $property->locationList();
                foreach($locationList as $locationIem){
                    $tenure_url = ($tenure == 'Sale') ? '-for-'.strtolower($tenure) : '-to-'.strtolower($tenure);
                    $sitemap_url = url('property'.$tenure_url.'/property-type/'.$propertyType.'/in/'.urlencode(strtolower($locationIem)));

                    // Check url if exist in sitemap hides
                    $hide_url = SitemapHides::where('url', $sitemap_url)->first();

                    if(!$hide_url){
                        Sitemap::addTag($sitemap_url, '', 'daily', '0.8');
                    }

                }
            }
        }
        return Sitemap::render();
    }

    /**
     * Sitemap for Properties
     */
    public function properties()
    {
        $property = new Property;

        // Properties
        $properties = $property->getAllForSitemap(false);

        foreach ($properties as $property)
        {
            $sitemap_url = url($property->url);

            // Check url if exist in sitemap hides
            $hide_url = SitemapHides::where('url', $sitemap_url)->first();

            if(! $hide_url){
                Sitemap::addTag($sitemap_url, $property->updated_at, 'daily', '0.8');
            }
        }

        return Sitemap::render();
    }



}
