@push('body_class') property-page -full-banner @endpush

@if(settings('members_area') == 1 && Auth::user() && Auth::user()->role_id == '4')
<section class="right-sticky sticky-top">
  <ul>
    <li class="notes rs-trigger ">
        <a id="rs-notes"
           class="sticky-link ba-animated modal-toggle"
           href="#"
           data-toggle="modal"
            data-modal-size="large"
           data-modal-type="create-note"
           data-modal-title="{{trans_fb('app.app_Create Property Note', 'Create Property Note')}}"
           data-delete-type="alert"
           data-target="#global-modal"
           >
            <i class="fas fa-edit"></i>
        </a>
        <a class="sticky-title rs-show modal-toggle"
           href="#"
           data-toggle="modal"
           data-modalSize="large"
           data-modal-type="create-note"
           data-modal-title="{{trans_fb('app.app_Create Property Note', 'Create Property Note')}}"
           data-delete-type="alert"
           data-target="#global-modal"
        >{{trans_fb('app.app_Make a Note', 'MAKE A NOTE')}}</a>
      <div class="clear"></div>
    </li>
  </ul>
</section>
@endif

<section class="property-section-style-1">
    <div class="container-fluid-disable">
        <div class="-wrap">
            <input class="property_id" type="hidden" value="{{ $property->id }}">
            <div class="-back-to-search">
                <div class="-back-to-search-wrap">
                    <a href="{{ $back_url }}" class="f-13 f-600"><i class="fas fa-angle-left"></i> BACK TO LISTINGS</a>
                </div>
            </div>

            <div class="property-slider-box">

                <div class="propertySlider-style-1">
                    @if($property->youtube_id)
                            <div class="slide-box">
                                <div class="slide-box-image fill">
                                    <span></span>
                                    <iframe width="100%" height="100%" src="https://www.youtube.com/embed/{{ $property->youtube_id }}" controls="0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                </div>
                            </div>
                    @endif

                    @if( count($property->propertyMediaPhotos) )
                        @php $i = 0; @endphp
                        @foreach( $property->propertyMediaPhotos as $media )


                            <div class="slide-box">
                                <div class="slide-box-image fill">
                                    <span></span>
                                    @if($i==1)
                                    <a href="{{ storage_url($media->photo_display) }}" data-thumb="{{ storage_url($media->photo_display) }}" data-fancybox="gallery" data-type="image">
                                        <img loading="lazy" class="u-opacity-08 b-lazy" src="{{ blankImg() }}" data-src="{{ storage_url($media->photo_display) }}" alt="{{$property->image_alt}} - thumb {{$i}}">
                                    </a>
                                     @endif
                                    <a href="{{ storage_url($media->photo_display) }}" data-thumb="{{ storage_url($media->photo_display) }}" data-fancybox="gallery" data-type="image">
                                        <img loading="lazy" class="u-opacity-08 b-lazy" src="{{ blankImg() }}" data-src="{{ storage_url($media->photo_display) }}" alt="{{$property->image_alt}} - thumb {{$i}}">
                                    </a>
                                </div>
                            </div>
                             @php $i++; @endphp
                        @endforeach
                    @else
                        <div class="slide-box">
                            <div class="slide-box-image">
                                <span></span>
                                <img loading="lazy" class="u-opacity-08 b-lazy" src="{{ blankImg() }}" data-src="{{ default_thumbnail() }}" alt="{{$property->image_alt}}">
                            </div>
                        </div>
                    @endif
                </div>

                @if( count($property->propertyMediaPhotos) > 1 )
                <div class="propertySliderNavCon {{ (count($property->propertyMediaPhotos)<5)?'-opacity-bg':'' }}">
                    <div class="propertySliderNav-style-1">
                        @if($property->youtube_id)
                           <div class="slide-box hide-video">
                                <div class="slide-box-image fill">
                                    <span></span>
                                    <img loading="lazy" src="{{ blankImg() }}" data-lazy="" alt="Photo thumbs">
                                </div>
                            </div>
                    @endif

                        @php $i = 0; @endphp
                        @foreach( $property->propertyMediaPhotos as $media )
                        @php $i++; @endphp
                            <div class="slide-box">
                                <div class="slide-box-image fill">
                                    <span></span>
                                    <img loading="lazy" src="{{ blankImg() }}" data-lazy="{{ storage_url($media->photo_display)  }}" alt="Photo thumbs - {{$i}}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="-cta-nav-container">
                    <div class="container">

                        <div class="property-slide-cta">
                            <ul>
                                @if ($property->property_status)
                                         <!--li class="property-status -hb-status {{ ($property->property_status=='Available')?'-blue':'' }}"><a class="f-13 f-600">{{ $property->property_status }}</a></li--->
                                @endif

                                @if( !empty($property->youtube_id) )
                                <li class="-ps-cta-tour">
                                    <a class="f-13 f-600" data-toggle="modal" data-target=".video-tour">
                                        <i class="far fa-play-circle"></i> VIDEO TOUR</a>
                                </li>@endif

                                @if( count($property->propertyMediaPhotos) > 1 )
                                <li class="-ps-cta-gallery">
                                    @php $i = 0; @endphp
                                    @foreach( $property->propertyMediaPhotos as $media )
                                    @php $i++; @endphp
                                        @if($i==1)
                                        <a href="{{ $media->photo_display }}" data-thumb="{{ $media->photo_display }}" data-fancybox="gallery-popup" data-type="image" class="f-13 f-600"> <i class="fas fa-expand-arrows-alt"></i> GALLERY</a>
                                        @else
                                        <a href="{{ $media->photo_display }}" class="u-d-none" data-thumb="{{ $media->photo_display }}" data-fancybox="gallery-popup" data-type="image"></a>
                                        @endif
                                    @endforeach
                                </li>@endif

                            </ul>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

@if( !empty($property->youtube_id) )
<div class="modal fade video-tour video-modal" tabindex="-1" role="dialog" aria-labelledby="video-tourModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="property-youtube">
            <iframe src="https://www.youtube.com/embed/{{ $property->youtube_id }}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        </div>
    </div>
  </div>
</div>
@endif

<section class="property-title-section">
    <div class="container">
        <div class="-wrap">
            <div class="row no-gutters">
                <div class="col-md-5">
                    <div class="-pts-title f-30 c-gray-28">{{$property->details_headline_v2}}</div>
                    <div class="-pts-attr">
                        <ul>
                            @if($property->DisplayPropertyAddress)
                                <li class="f-14  c-gray-23"><i class="fas fa-map-marker-alt c-dark-gray"></i> {{$property->DisplayPropertyAddress}}</li>
                            @endif
                            @if($property->ref)
                                <li class="f-14  c-gray-23"><span class="f-600">Ref:</span> {!!$property->ref!!}</li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="-pts-right-side">
                        <ul class="-layout">
                            <!--li class="-currency">

                                <a href="#" class="-active usd -currency-js-change" data-currency="usd" data-price="{!! strip_tags($property->DisplayPrice) !!}">USD</a>
                                <a href="#" class="gbp -currency-js-change" data-currency="gbp" data-price="{!! strip_tags($property->DisplayPriceGBP) !!}">GBP</a>
                                <a href="#" class="eur -currency-js-change" data-currency="eur" data-price="{!! strip_tags($property->DisplayPriceEUR) !!}">EUR</a>
                                <a href="#" class="bbd -currency-js-change" data-currency="bbd" data-price="{!! strip_tags($property->DisplayPriceBBD) !!}">BBD</a>

                                @if(0)
                                <a href="#" class="-active gbp -currency-js-change" data-currency="gbp" data-price="{!! strip_tags($property->display_price) !!}">&pound;</a>
                                <a href="#" class="usd -currency-js-change" data-currency="usd" data-price="{!! strip_tags($property->DisplayPriceUSD) !!}">&dollar;</a>
                                @endif

                            </li--->
                            <li class="-price f-30 f-600 -js-price-display c-gray-28">{!! $property->display_price !!}</li>
                            <li class="-cta-container">
                               @if(Auth::check() && settings('members_area') == 1)
                                @if($property->CheckShortlistIp)
                                    <a href="#"
                                    class="-pts-cta -shortlist f-13 f-600 shortlist shortlist-add {{ ($property->CheckShortlistIp) ? 'shortlist-confirm-action' : '' }}"
                                    data-url="{{ url('shortlist/ajax/add') }}"
                                    data-property-id="{{ $property->id }}"
                                    data-save-text="SAVE PROPERTY"
                                    data-remove-text="REMOVE"
                                    >
                                     {!! ($property->CheckShortlistIp) ? '<i class="fas fa-times"></i> REMOVE' : '<i class="fas fa-heart"></i>  SAVE PROPERTY' !!}
                                    </a>
                                     @else
                                        <a class="-pts-cta -shortlist f-13 f-600 shortlist-add {{ ($property->CheckShortlistIp) ? 'shortlist-confirm-action' : '' }}" data-url="{{ url('shortlist/ajax/add') }}" data-property-id="{{ $property->id }}" data-save-text="" data-remove-text="" >
                                             <i class="fas fa-times"></i> REMOVED PROPERTY
                                        </a>

                                     @endif
                                @else
                                    <a href="#"
                                    class="-pts-cta -shortlist f-13 f-600 shortlist" data-toggle="modal" data-target=".member-login-account">
                                        <i class="fas fa-heart"></i> SAVE PROPERTY
                                    </a>
                                @endif
                                <a href="#interested" class="-pts-cta -request-info f-13 f-600 linkSlide">REQUEST INFO</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="page-content u-mt2 u-mb4">
    <div class="container">
        <div class="property-content-wrapper">
            <div class="row">
                <div class="col-lg-7 col-md-7">

                    <div class="property-info-container">

                        <div class="property-info-section -key-features">
                            <div class="property-attributes">
                                @if(0)
                                <ul class="-layout">
                                    @if(!empty($property->beds))
                                    <li class="">
                                        <div class="pw-aligner">
                                            <span class="-icon-pis icon-beds"></span>
                                            <span class="-label"> {{$property->beds}} Beds</span>
                                        </div>
                                    </li>@endif
                                    @if(!empty($property->baths))
                                    <li>
                                        <div class="pw-aligner">
                                            <span class="-icon-pis icon-shower"></span>
                                            <span class="-label"> {{$property->baths}} Baths</span>
                                        </div>
                                    </li>@endif
                                    @if(!empty($property->land_area))
                                    <li>
                                        <div class="pw-aligner">
                                            <span class="-icon-pis icon-map-2"></span>
                                            <span class="-label"> {{$property->DisplayLand}}</span>
                                        </div>
                                    </li>@endif
                                    @if(!empty($property->internal_area))
                                    <li>
                                        <div class="pw-aligner">
                                            <span class="-icon-pis icon-bed"></span>
                                            <span class="-label"> {{$property->DisplayInternal}}</span>
                                        </div>
                                    </li>@endif
                                </ul>
                                @endif


                                <ul class="-layout">
                                    @if(!empty($property->beds))
                                    <li class="">
                                        <div class="pw-aligner">
                                            <div class="-icon-image">
                                                <img loading="lazy" class="-icon-img b-lazy" src="{{ blankImg() }}" data-src="{{themeAsset('images/svg/double-bed.svg')}}" alt="Beds">
                                            </div>
                                            <span class="-label"> {{$property->beds}} Beds</span>
                                        </div>
                                    </li>@endif
                                    @if(!empty($property->baths))
                                    <li>
                                        <div class="pw-aligner">
                                            <img loading="lazy" class="-icon-img b-lazy" src="{{ blankImg() }}" data-src="{{themeAsset('images/svg/bath.svg')}}" alt="Bath">
                                            <span class="-label"> {{$property->baths}} Baths</span>
                                        </div>
                                    </li>@endif
                                    @if(!empty($property->land_area))
                                    <li>
                                        <div class="pw-aligner">
                                            <img loading="lazy" class="-icon-img b-lazy" src="{{ blankImg() }}" data-src="{{themeAsset('images/icons/icon-area-land-2021.png')}}" alt="Land Area">
                                            <span class="-label"> {{$property->DisplayLand}}</span>
                                        </div>
                                    </li>@endif
                                    @if(!empty($property->internal_area))
                                    <li>
                                        <div class="pw-aligner">
                                            <img loading="lazy" class="-icon-img b-lazy" src="{{ blankImg() }}" data-src="{{themeAsset('images/icons/icon-area-internal-2021.png')}}" alt="Internal Area">
                                            <span class="-label"> {{$property->DisplayInternal}}</span>
                                        </div>
                                    </li>@endif
                                </ul>


                            </div>
                        </div>
                        <div class="property-info-section -key-features">

                            @if( !empty($property->add_info) )
                            <h3 class="-title f-30 f-400">Key Features</h3>
                            <div class="p-list-item">
                                <div class="row">
                                    @foreach ($property->DisplayAddInfoArray as $value)
                                    <div class="col-sm-6 col-6">
                                        <div class="-item f-16">
                                            {{$value}}
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <div class="p-list-item">
                                <div class="row">

                                    <div class="col-sm-6 col-6">
                                        <div class="-item f-16">
                                            <span class="f-600">Mode:</span> {{ $property->ModeDisplay }}
                                        </div>
                                    </div>

                                    @if(!empty($property->Community))
                                    <div class="col-sm-6 col-6">
                                        <div class="-item f-16">
                                            <span class="f-600">Community:</span> {{ $property->Community }}
                                        </div>
                                    </div>
                                    @endif

                                    @if(!empty($property->property_type_ids))
                                    <div class="col-sm-6 col-6">
                                        <div class="-item f-16">
                                            <span class="f-600">Type:</span> {{ $property->PropertyTypeNameAll }}
                                        </div>
                                    </div>
                                    @endif

                                    @if(0)
                                        @if(!empty($property->property_type_id))
                                        <div class="col-sm-6 col-6">
                                            <div class="-item f-16">
                                                <span class="f-600">Type:</span> {{ $property->PropertyTypeName }}
                                            </div>
                                        </div>
                                        @endif
                                        @if(!empty($property->Subtype))
                                        <div class="col-sm-6 col-6">
                                            <div class="-item f-16">
                                                <span class="f-600">Subtype:</span> {{ $property->Subtype }}
                                            </div>
                                        </div>
                                        @endif
                                    @endif

                                </div>
                            </div>

                            <div class="property-share-container">
                                <ul class="-layout">
                                    @if(settings('pdf_view') == 1)
                                    <li class="-psc-pdf f-three"><a href="{{ $property->property_pdf_path != null ? storage_url($property->property_pdf_path) : url('/download-pdf/'.$property->id) }}" target="_blank">DOWNLOAD PDF</a></li>@endif
                                    <li class="-psc-label f-three ">Share this listing</li>
                                    <li class="-psc-share"><a href="mailto:?Subject={{$property->details_headline_v2}}&amp;Body={{$property->url}}" class="-icon" target="_blank"><i class="fas fa-envelope"></i></a></li>
                                    <li class="-psc-share"><a href="https://twitter.com/share?url={{$property->url}}" class="-icon" target="_blank"><i class="fab fa-twitter"></i></a></li>
                                    <li class="-psc-share"><a href="https://www.facebook.com/sharer/sharer.php?u={{$property->url}}" class="-icon" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="property-info-section">
                            <h3 class="-title f-25 f-400">About This Property</h3>
                            <div class="-description f-16 generic-text-content">
                                {!! nl2br($property->description) !!}
                            </div>
                        </div>

                        @if( count($property->propertyMediaDocuments) )
                        <div class="property-info-section">
                            <h3 class="-title f-30 f-400">Additional Information</h3>
                            <div class="p-link-list-container">
                                <ul class="p-link-list">
                                    @foreach( $property->propertyMediaDocuments as $media )
                                    <li>
                                        <a href="{{ $media->path }}" class="f-600 f-15 text-uppercase"
                                            target="_blank"
                                            ><i class="fas fa-download"></i> {{ $media->DisplayTitle }}</a>
                                    </li>
                                    @endforeach
                                </ul>
                                @if(0)
                                <ul class="p-link-list">
                                    <li><a href="" class="f-600 f-15"><i class="fas fa-download"></i> FLOORPLAN GROUND FLOOR</a></li>
                                    <li><a href="" class="f-600 f-15"><i class="fas fa-download"></i> FLOORPLAN FIRST FLOOR</a></li>
                                    <li><a href="" class="f-600 f-15"><i class="fas fa-download"></i> OTHER DOCUMENTS</a></li>
                                    <li><a href="" class="f-600 f-15"><i class="fas fa-download"></i> OTHER DOCUMENTS</a></li>
                                </ul>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if( !empty($property->add_amenities) )
                        <div class="property-info-section">
                            <h3 class="-title f-30 f-400">Amenities</h3>
                            <div class="p-list-item">
                                <div class="row">
                                    @foreach ($property->DisplayAmenitiesArray as $value)
                                    <div class="col-sm-6 col-6">
                                        <div class="-item f-16">
                                            {{$value}}
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>


                </div>
                <div class="col-lg-5 col-md-5">
                    <div class="rhs-container -full side-bar-content">

                        <div class="row col-2-responsive">
                            <div class="col-md-12 col-sm-6 col-6">
                                <div class="rhs-contact-form c-bg-dark-gray u-mb2">
                                    <div class="-title f-28 f-two text-center text-white">Enquire today</div>
                                    @include('frontend.demo1.forms.property-enquiry-sidebar')
                                </div>
                            </div>
                            @if(!empty($property->latitude) && !empty($property->longitude))
                            <div class="col-md-12 col-sm-6 col-6">
                                <div id="property-map" class="property-map-style map-attr u-mb2" data-lat="{{ $property->latitude }}" data-lng="{{ $property->longitude }}"></div>
                            </div>@endif
                            <div class="col-md-12 col-sm-12 col-12">
                                @include('frontend.demo1.forms.property-mortgage')
                            </div>
                             <div class="col-md-12 col-sm-12 col-12">
                                @include('frontend.demo1.forms.stamp-duty-calculator')
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@include('frontend.demo1.forms.property-enquiry-bottom')
@widget('similarProperties', ['property' => $property, 'title' => 'Similar Properties'])
@include('frontend.demo1.forms.generic-bottom')

@push('frontend_scripts')
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;key={{settings('google_map_api') }}" defer></script>
@endpush
