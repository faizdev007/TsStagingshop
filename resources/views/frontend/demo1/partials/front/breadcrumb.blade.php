<div class="np-block">

    <style>
        .breadcumbs-container{
            display: flex;
            width: 95%;
        }

        .header-pagination{
            padding: 18px 0 0 0;
            font-size: 15px;
        }

        .header-pagination a:hover{
            color: #2fbfbd;
        }

        .breadcumbs-and-pagination{
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-right: 20px;
        }

        .breadcrumbs-border-top{
            display: none;
        }

        @media only screen and (max-width: 777px) {
            .breadcumbs-container{
                display: flex;
                width: 100%;
            }
            .breadcumbs-and-pagination{
                justify-content: center;
                margin: 0;
                width: 100%;
                max-height: 50px;
                align-items: center;
            }
            .header-pagination {
                padding: 10px 0;
                font-size: 12px;
            }

            .breadcrumbs-border-top{
                display: block;
                border-bottom: 4px solid #2fbfbd;
            }

            .np-block span.border{
                display: none;
            }

            .property-title{
                display:block;
                padding: 0;
            }
        }
    </style>

    <div class="centering">
        <?php

use Illuminate\Support\Str;

        $segment1 = \request()->segment(1);
        $segment2 = \request()->segment(2);
        if ($segment1 == "search") {
            if (request()->segment(3))
                $navData = ucwords(\request()->segment(3));
            else
                $navData = ucwords(\request()->segment(1));
        } else if ($segment1 == "blogs")
            $navData = "Blogs";
        else if ($segment1 == "blog-news")
            $navData = "Blog & News";
        else if ($segment1 == "properties")
            $navData = "List";
        else
            $navData = ucwords($segment1);
        $navData = str_replace('-', ' ', $navData);

        $breadcrumbs_url = '';
        if (in_array($segment1, array('testimonials', 'our-partners', 'our-office'))) {
            $breadcrumbs_title = 'About us';
            $breadcrumbs_url = 'aboutus';
        }
        ?>

        <span class="breadcrumbs-border-top"></span>

        <div class="breadcumbs-container">
            <div class="breadcumbs-and-pagination">
                <div>
                    <ul class="breadcrumbs" itemscope="itemscope" itemtype="http://schema.org/BreadcrumbList">
                        <li>YOU ARE HERE:</li>
                        <li>
                            <a itemscope="itemscope" itemtype="http://schema.org/Thing" itemprop="item"
                               href="{{url($breadcrumbs_url)}}">
                                <span itemprop="name"><?php if (isset($breadcrumbs_title) && $breadcrumbs_title != "") echo $breadcrumbs_title; else echo 'Koh Samui Real Estate';?></span>
                                &gt;

                            </a>
                            <meta itemprop="position" content="0">
                        </li>
                        <li class="active" itemprop="itemListElement" itemscope="itemscope"
                            itemtype="http://schema.org/ListItem">
                            <span itemprop="name"><?php echo $navData; ?></span>
                            <meta itemprop="position" content="<?php if ($segment2 != "") echo '2'; else echo '1';?>">
                        </li>
                    </ul>
                </div>

		@if(\Illuminate\Support\Facades\Route::currentRouteName() == 'properties.get')
                    <div class="header-pagination">
                        <a class="prev" href="{{$data['property']->getPrevious()->getUrl()}}">&lt;&lt; Previous | </a>
                        <a class="back" href="{{url('search')}}">Back to search Results</a>
                        <a class="next" href="{{$data['property']->getNext()->getUrl()}}"> | Next &gt;&gt;</a>
                    </div>
                @endif
            </div>


            <div id="share">
                <a href="#link" class="share"><i class="icon-share"></i></a>

                <div id="link">

                    <div class="social social-sharing">

                        <a rel="nofollow" class="share-facebook"
                           href="https://www.facebook.com/sharer.php?u=conradproperties.asia"
                           target="_blank">
                            <span aria-hidden="true" class="icon icon-facebook"></span>
                            <span class="share-title">Like</span>

                        </a>
                        <a rel="nofollow" class="share-twitter"
                           href="https://twitter.com/share?url=Property_Conrad&amp;text=Description of page"
                           target="_blank">
                            <span aria-hidden="true" class="icon icon-twitter"></span>
                            <span class="share-title">Tweet</span>
                            {{--<span class="share-count is-loaded">6200</span>--}}
                        </a>
                        <a rel="nofollow" class="share-pinterest"
                           href="https://pinterest.com/pin/create/button/?url=&media=https%3A//www.pinterest.com/conradprop/&description="
                           target="_blank">
                            <span aria-hidden="true" class="icon icon-pinterest"></span>
                            <span class="share-title">Pinterest</span>

                        </a>
                        <a rel="nofollow" class="share-google"
                           href="https://plus.google.com/share?url=https%3A//plus.google.com/%2BConradpropertiesAsia"
                           target="_blank">
                            <!-- Cannot get Google+ share count with JS yet -->
                            <span aria-hidden="true" class="icon icon-gplus"></span>
                            <span class="share-title">Google+</span>
                            {{--<span class="share-count is-loaded">+1</span>--}}
                        </a>

                    </div>

                </div>
            </div>

        </div>

        <div class="clear"></div>

        <hr>
        <span class="border"></span>
    </div>

</div>

