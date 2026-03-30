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

if (in_array($segment1, array('global-luxury-search'))) {
    $breadcrumbs_title = 'International Real Estate';
    $breadcrumbs_url = url('global-luxury-search');
}

?>
<ul class="breadcrumbs" itemscope="itemscope" itemtype="http://schema.org/BreadcrumbList">
    <li><a  itemscope="itemscope" itemtype="http://schema.org/Thing" itemprop="item"
            href="{{url($breadcrumbs_url)}}">
            <span itemprop="name" class="p-0"><?php if (isset($breadcrumbs_title) && $breadcrumbs_title != "") echo $breadcrumbs_title; else echo 'Home';?></span>
        </a><meta itemprop="position" content="0">
    </li>
    <li><span>/</span></li>
    <li itemprop="itemListElement" itemscope="itemscope"
        itemtype="http://schema.org/ListItem">
        <span itemprop="name" class="p-0"><?php echo $navData; ?></span>
        <meta itemprop="position" content="<?php if ($segment2 != "") echo '2'; else echo '1';?>">
    </li>
</ul>
