<section class="page-title c-bg-gray-8 -placeholder lazyBg"
    @if(!empty($page->photo))
    style="background-image: url({{ blankImg() }})"
    {!!$page->BannerDataBG!!}  @endif>
    <div class="container">
        <div class="-wrap">
            <div class="-inner-wrap">
                <h1 class="-page-title-heading f-four f-60 f-500 c-white text-center">
                    {!! $page->title !!}
					<span class="-border {{ !empty($page->header_title)?'border-remove-title':'' }}"></span>
				</h1>
                @if($page->header_title)
                    <h2 class="-page-subtitle-heading f-26 f-two f-500 c-white d-block text-center">{{ $page->header_title }}</h2>
                @endif
            </div>
        </div>
    </div>
</section>

<section class="page-content u-mt405 u-mb4">
    <div class="container">
        <div class="property-content-wrapper">
            <div class="row">
                <div class="col-lg-8 col-md-8">
                    <div class="generic-text-content">
						{!! $page->content !!}
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="rhs-container">
                        <div class="rhs-links c-bg-text-color4" >
                            <ul class='-rhs-list'>

                                <li class="{{ (($page->route=='property-management')?'active':'') }}"><a href="{{ lang_url('property-management') }}" class="f-15 c-dark-gray text-uppercase">
                                    <span class="pw-icon icon-arrow-right-style-1 c-primary"></span> Property Management</a></li>

                                <li class="{{ (($page->route=='property-consulting')?'active':'') }}"><a href="{{ lang_url('property-consulting') }}" class="f-15 c-dark-gray text-uppercase"><span class="pw-icon icon-arrow-right-style-1 c-primary"></span> Property Consultancy</a></li>

                                <li class="{{ (($page->route=='commercial-real-estate-services')?'active -small':'') }}"><a href="{{ lang_url('commercial-real-estate-services') }}" class="f-15 c-dark-gray  text-uppercase"><span class="pw-icon icon-arrow-right-style-1 c-primary"></span> Commercial Real Estate Services</a></li>

                                <li class="{{ (($page->route=='vacation-rentals')?'active':'') }}"><a href="{{ lang_url('vacation-rentals') }}" class="f-15 c-dark-gray text-uppercase"><span class="pw-icon icon-arrow-right-style-1 c-primary"></span> Vacation Rentals</a></li>

                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('frontend.demo1.forms.generic-bottom')
