<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\Post;

class LatestNews extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'template' => 1,
        'title' => 'More Latest News',
        'except' => false
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $themeDirectory = themeOptions();

        // See If Posts Have Been Passed by an alternative means...
        if(in_array('posts', $this->config))
        {
            // Posts Already Set
            $posts = $this->config['posts'];
            $is_location = $this->config['location'];
            $property = $this->config['property'];
        }
        else
        {
            // Find latest news articles
            // "template 1" requires 3 items; "template 2" requires 2
            $limit = 2;

            $except = $this->config['except'];
            if(!empty($except)){
                $posts = Post::published()->where([['id','!=',$except]])->orderBy('date_published', 'DESC')->take($limit)->get();
            }else{
                $posts = Post::published()->orderBy('date_published', 'DESC')->take($limit)->get();
            }

            $is_location = false;
            $property = NULL;
        }


        return view('frontend.'.$themeDirectory.'.widgets.latest_news' . $this->config['template'], [
            'config' => $this->config,
            'posts' => $posts,
            'property' => $property,
            'is_location' => $is_location,
        ]);

    }
}
