@if(count($properties))
<section class="property-list-container u-pt0 u-pb0">
    <div class="container">
        <div class="wrap">
            <div class="property-list-style-1 u-mt2">
                <div class="row">
                    @foreach ($properties as $property)
                    <div class="col-6">
                        <div class="dynamicHeight">
                            <div class="p-item">
                                <div class="images">
                                    @if ($property->property_status)
                                    <div class="property-status-box">
                                        <div class="property-status">
                                            <span class="c-bg-primary c-white f-white u-inline-block f-13 f-bold">{{ $property->property_status }}</span>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="property-price-box">
                                        <div class="property-price">
                                            <span class="c-bg-propprice c-white f-bold f-16 u-inline-block">{!! $property->display_price !!}</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8 col-sm-12 col-xs-8">
                                            @if( count($property->propertyMediaPhotos) )
                                            @php $first = true; @endphp
                                            @foreach($property->propertyMediaPhotos as $media)
                                                @if($first)
                                                    @php $first = false; @endphp
                                                    <div class="grid-image">
                                                        <div class="gi-slide">
                                                            <div class="main img {{ ($media->orientation == 'portrait' ? '' : 'fill') }}">
                                                                <span></span>
                                                                    <a href="{{lang_url($property->url)}}">
                                                                        <img class="b-lazy" src="{{ blankImg() }}" data-src="{{ $property->primary_photo }}" alt="{{$property->image_alt}}">
                                                                    </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                            @else
                                            <div class="grid-image">
                                                <div class="gi-slide">
                                                    <div class="main img">
                                                        <span></span>
                                                        <a href="{{ lang_url($property->url)}}">
                                                            <img class="b-lazy" src="{{ blankImg() }}" data-src="{{ default_thumbnail() }}" alt="{{$property->image_alt}}">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-4 s-img-con">

                                            <div class="s-img img {{ ($property->secondary_photo_flag)?'fill':'' }}">
                                                <span></span>
                                                <a href="{{lang_url($property->url)}}">
                                                    <img class="b-lazy" src="{{ blankImg() }}" data-src="{{ $property->secondary_photo }}" alt="{{$property->image_alt}} - 2">
                                                </a>
                                            </div>
                                            <div class="t-img img {{ ($property->third_photo_flag)?'fill':'' }}">
                                                <span></span>
                                                <a href="{{lang_url($property->url)}}">
                                                    <img class="b-lazy" src="{{ blankImg() }}" data-src="{{ $property->third_photo }}" alt="{{$property->image_alt}} - 3">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-info">
                                    <div class="row">
                                        <div class="col-sm-12 col-xl-7">
                                            <div class="title-box">
                                                <span class="f-18 c-dark f-semibold u-block p-title fix-line">{{ $property->search_headline }}</span>
                                                <span class="f-13 c-darkcopy u-block fix-line">{{ $property->DisplayPropertyAddress }}</span>
                                                <span class="f-13 c-darkcopy u-block fix-line">{!! $property->BedBathArea !!}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-5">
                                            <div class="float-xl-right">
                                                @if(settings('is_demo') == 'Yes')
                                                    <a class="button -tertiary f-two f-16 f-semibold u-inline-block" href="{{$property->url}}"
                                                       data-toggle="dropdown"
                                                       aria-haspopup="true"
                                                       aria-expanded="false"
                                                    >&nbsp; {{ trans_fb('app.app_More_Details', 'More Details') }} &nbsp;</a>
                                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                        <a class="dropdown-item" href="{{$property->url}}?template=1">Template 1</a>
                                                        <a class="dropdown-item" href="{{$property->url}}?template=2">Template 2</a>
                                                    </div>
                                                @else
                                                    <a class="button -tertiary f-two f-16 f-semibold u-inline-block" href="{{lang_url($property->url)}}">&nbsp;{{ trans_fb('app.app_More_Details', 'more details') }}&nbsp;</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif
