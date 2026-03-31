@push('body_class')detail-page @endpush

@extends('frontend.demo1.layouts.frontend')

@section('main_content')

<!-- Bread Crumb -->
<section class="inner-hero bread-block">
    <div class="container">
        <div class="row">
            <div class="col-md-7 col-9">
                <div class="pager-bx">
                     @include('frontend.demo1.partials.front.pw.breadcrumb')
                </div>
            </div>
            <div class="col-md-5 col-3">
                <div class="backstepbx">
                    <a href="{{url('property-for-sale')}}"><i class="fas fa-angle-left"></i> BACK <span class="u-dnone-767">TO SEARCH RESULTS</span></a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Bread Crumb -->


<!-- Property Listing -->
<section class="property-deta-wrp u-circle-style-1">
    <div class="u-circle-style-2">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-7 col-md-12 col-sm-12">
                    <div class="prod-right-lst d-lg-none">
                        
                     @include('frontend.demo1.partials.front.properties.property-price')
                 </div>
                    <div class="prod-data-slider">
                        <div class="prod-data-slider-head">
                        <h1>{{$property->details_headline_v2}}</h1>
                        </div>

                        @if( count($property->propertyMediaPhotos) > 1 )
                            <div class="products-slider">
                                @if ($property->state_display)
                                    <div class="property-grid--featured-ribbon">{{ $property->state_display }}</div>
                                @endif
                                
                                @if(Auth::check() && settings('members_area') == 1)

                                    @php
                                        $isSaved = $property->CheckShortlistIp;
                                    @endphp

                                    <a href="#"
                                    class="-pts-cta -shortlist f-13 f-600 shortlist-add {{ $isSaved ? 'shortlist-confirm-action' : '' }}"
                                    data-url="{{ url('shortlist/ajax/add') }}"
                                    data-property-id="{{ $property->id }}"
                                    data-save-text="SAVE PROPERTY"
                                    data-remove-text="REMOVE">

                                        <i class="{{ $isSaved ? 'fas fa-heart' : 'far fa-heart' }}"></i>

                                    </a>

                                @else

                                    <a href="#"
                                    class="-pts-cta -shortlist f-13 f-600 shortlist"
                                    data-bs-toggle="modal"
                                    data-bs-target=".member-login-account">

                                        <i class="far fa-heart"></i>

                                    </a>

                                @endif

                                <div class="cSlider cSlider--single">
                                    @php $i = 0; @endphp
                                    @foreach( $property->propertyMediaPhotos as $media )
                                    @php $i++; @endphp
                                        <div class="cSlider__item">
                                            <div class="big-img-bx">
                                                <div class="big-img-bx--inner go-center fill">
                                                    <a href="{{ storage_url($media->photo_display)  }}" data-thumb="{{$media->photo_display}}" data-fancybox="community" data-type="image">
                                                        <img loading="lazy" src="{{ blankImg() }}" data-lazy="{{ storage_url($media->photo_display)  }}" alt="Photo thumbs - {{$i}}" sizes="(max-width:768px) 400px, (max-width:1200px) 700px, 1200px"
                                                        srcset="
                                                            {{ storage_url('small/'.$media->photo_display) }} 400w,
                                                            {{ storage_url('medium/'.$media->photo_display) }} 600w,
                                                            {{ storage_url($media->photo_display) }} 1000w
                                                        "
                                                        sizes="(max-width: 768px) 100vw, 570px"
                                                        >
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="cSlider cSlider--nav u-dnone-500">
                                    @foreach( $property->propertyMediaPhotos as $media )
                                    <div class="cSlider__item">
                                        <div class="small-img-bx">
                                            <img loading="lazy" src="{{ storage_url($media->photo_display)  }}" alt="{{($property->alt_title . '- thumb '.$loop->iteration)}}"
                                            srcset="
                                                {{ storage_url('small/'.$media->photo_display) }} 400w,
                                                {{ storage_url('medium/'.$media->photo_display) }} 600w,
                                                {{ storage_url($media->photo_display) }} 1000w
                                            "
                                            sizes="(max-width: 768px) 100vw, 570px"
                                            >
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            
                            </div>
                        @else
                            <img loading="lazy" src="{{ themeAsset('images/placeholder/large.jpg')  }}" alt="Photo thumbs">
                        @endif
                    </div>
                </div>
            <div class="col-xl-4 col-lg-5 col-md-12 col-sm-12">
            <div class="prod-right-lst price-sm-hide">
               
                @include('frontend.demo1.partials.front.properties.property-price')
                
               
                <div id="property-form-wrap" class="pro-inqury-frm">
                    <div class="d-lg-block d-xl-block d-xxl-block">
                        <h3>Enquire about this property</h3>
                       @include('frontend.demo1.forms.property-enquiry-sidebar')
                    </div>
                    <div class="d-md-block">
                        @include('frontend.demo1.partials.front.properties.property-attributes')
                    </div>
                </div>
                
            </div>
            </div>

            </div>

            
               <div class="row">
                <div class="col-md-12">
                    <div class="pro-description">
                        <h3>Description</h3>
                        <div class="u-generic-text-content">
                            {!! nl2br($property->description) !!}
                        </div>
                   
                    @if( count($property->propertyMediaDocuments) )
                        <div class="property-info-section mt-3">                           
                            <div class="p-link-list-container">
                                
                                    @foreach( $property->propertyMediaDocuments as $media )
                                  
                                        <a href="{{ storage_url($media->path) }}" class="f-600 f-13 text-uppercase btn-main download-btn"
                                            target="_blank"
                                            ><i class="fas fa-download"></i> {{ $media->DisplayTitle }}</a>
                                   
                                    @endforeach
                                </ul>
                               
                            </div>
                        </div>
                        @endif
<!-- /.single-additional -->
                        @if(settings('pdf_view') == 1)
                            <div class="single-additional" style="margin-top: 30px;">
                                <a href="{{ isset($property->property_pdf_path) ? storage_url($property->property_pdf_path) : $property->pdf_url }}" class="-primary button download-pdf" target="_blank">
                                    Download PDF
                                </a>
                            </div>
                        @endif
<!-- /.single-additional -->
                        </div>
                    @if($property->add_info)
                    <div class="pro-description feature-lst">
                        <h3>Key Features</h3>
                        <ul>
                            @foreach($property->DisplayAddInfoArray as $feature)
                            <li>
                                <div class="customcheck">
                                    <input class="styled-checkbox" id="ch-{{$loop->iteration}}" type="checkbox" value="value" checked>
                                    <label for="ch-{{$loop->iteration}}"><span>{{ $feature }}</span></label>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if($property->add_amenities)
                    <div class="pro-description feature-lst">
                        <h3>Amenities</h3>
                        <ul>
                            @foreach($property->DisplayAmenistiesInfoArray as $feature)
                            <li>
                                <div class="customcheck">
                                    <input class="styled-checkbox" id="ch-{{$loop->iteration}}" type="checkbox" value="value" checked>
                                    <label for="ch-{{$loop->iteration}}"><span>{{ $feature }}</span></label>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                      @if( ($property->latitude && $property->longitude != "") || (isset($property->youtube_id)) )
                    <div class="pro-description">
                        <div class="row">
                            @if($property->latitude && $property->longitude != "" )
                            <div class="col-lg-6 {{ (isset($property->youtube_id) && $property->youtube_id!='')?'col-lg-6':'col-lg-12' }}">
                                <div class="fream-bx mb-4 mb-lg-0">
                                    <h3>Location</h3>
                                     <div id="map" class="property-map-style map-attr u-mb2" data-lat="{{ $property->latitude }}" data-lng="{{ $property->longitude }}" ></div>
                                </div>
                            </div>
                            @endif
                            @if(isset($property->youtube_id) && $property->youtube_id!='')
                            <div class="{{ ($property->latitude && $property->longitude != "" )?'col-lg-6':'col-lg-12' }}">
                                <div class="fream-bx">
                                    <h3>Property Video</h3>
                                    <iframe width="100%" height="100%" src="https://www.youtube.com/embed/{{$property->youtube_id}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>

@widget('CommunityView', ['property' => $property])

@widget('SimilarProperties', ['property' => $property, 'title' => 'Similar Properties'])

@endsection

@push('frontend_scripts')
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;key={{settings('google_map_api') }}" defer></script>
@endpush

@push('frontend_css')
    <style>
        .property-page.detail-page .prod-data-slider-head {
            flex-direction: column;
            margin-bottom: 14px;
        }

        .property-page.detail-page .prod-data-slider-head > h1 {
            margin-bottom: 0;
        }
    </style>
@endpush