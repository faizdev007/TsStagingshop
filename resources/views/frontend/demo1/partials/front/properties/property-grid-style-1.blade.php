




@if(count($properties))
<div class="property-grid--item u-item-shadowed--style-1 position-relative">
    @if ($property->state_display)
        <div class="property-grid--featured-ribbon">{{$property->state_display}}</div>
    @endif
    <div class="property-grid-thumb--slick">
        @if( count($property->propertySearchMediaPhotos) )
            @php $first = true; @endphp
            @foreach($property->propertySearchMediaPhotos as $key=>$media)
                @php $linksstr = OptimizeImgLinks($media->path); @endphp
                <div class="property-grid--image">
                    <div class="property-grid--image-inner u-height-246 go-center fill">
                        <a href="{{lang_url($property->url)}}">
                        <img class="b-lazy-slick property-grid--image-item"
                        src="{{blankImg(themeAsset('images/placeholder/large.jpg'))}}"
                        data-src="{{ storage_url($media->path) }}"
                        alt="{{$property->image_alt}} photo"
                        loading="{{$key == 0 ? 'eager' : 'lazy'}}" fetchpriority="{{$key == 0 ? 'high' : 'low'}}"
                        srcset="{{$linksstr}}"
                        sizes="(max-width: 768px) 100vw, 570px"
                        decoding="async"
                        height="286px">
                        </a>
                    </div>
                    
                </div>
            @endforeach
        @else
                <div class="grid-image">
                    <div class="gi-slide">
                        <div class="main img">
                            <span></span>
                            <a href="{{ lang_url($property->url)}}">
                            <img class="b-lazy-slick property-grid--image-item"
                            src="{{ blankImg(themeAsset('images/placeholder/large.jpg')) }}"
                            data-src="{{ storage_url('path/to/default/image.jpg') }}" 
                            alt="{{$property->image_alt}} photo"
                            width="1000px"
                            loading="lazy"
                            height="667px">
                            </a>
                        </div>
                    </div>
                </div>
        @endif
    </div>
    <div class="property-grid--content bg-white">
        <div class="property-grid--content-title f-16 c-gray f-line-height-102 mb-2">
            <a href="{{lang_url($property->url)}}" class="c-gray-link f-semibold u-hover-opacity-70">{{ $property->search_headline }}</a>
        </div>
        <div class="property-grid--content-body f-13 c-gray f-regular mb-2">
            {!! Str::limit(strip_tags($property->description), 140) !!}
        </div>
        <div class="f-16 c-gray f-semibold mb-3 u-min-height-24 property-address">
            <span class="f-16 c-gray-link f-semibold u-hover-opacity-70">{{ $property->DisplayPropertyAddress }}</span>
        </div>
        <!-- <br>
        <br> -->
        <div class="property-grid--attr f-16 c-gray d-flex justify-content-between f-regular mb-4">
            <div class="d-flex align-content-center">
                @if($property->beds)
                <div class="property-grid--attr-item me-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"><path fill="black" d="M2 17v-5.025q0-.825.588-1.4T4 10V7q0-.825.588-1.413T6 5h12q.825 0 1.413.588T20 7v3q.825 0 1.413.588T22 12v5h-1.35l-.475 1.5q-.075.225-.263.363T19.5 19q-.25 0-.425-.163t-.25-.387L18.35 17H5.65l-.475 1.5q-.075.225-.262.363T4.5 19q-.25 0-.425-.163t-.25-.387L3.35 17H2Zm11-7h5V7h-5v3Zm-7 0h5V7H6v3Zm-2 5h16v-3H4v3Zm16 0H4h16Z"/></svg>
                    <span>{{$property->beds}}</span>         
                </div>@endif
                @if($property->baths)
                <div class="property-grid--attr-item">
                   <svg xmlns="http://www.w3.org/2000/svg" width="30" height="25" viewBox="0 0 24 24"><path fill="black" d="M21 14v1c0 1.91-1.07 3.57-2.65 4.41L19 22h-2l-.5-2h-9L7 22H5l.65-2.59A4.987 4.987 0 0 1 3 15v-1H2v-2h18V5a1 1 0 0 0-1-1c-.5 0-.88.34-1 .79c.63.54 1 1.34 1 2.21h-6a3 3 0 0 1 3-3h.17c.41-1.16 1.52-2 2.83-2a3 3 0 0 1 3 3v9h-1m-2 0H5v1a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3v-1Z"/></svg>
                    <span>{{$property->baths}}</span>
                </div>
                @else
                {{--<div class="property-grid--attr-item" ></div>--}}
                @endif
            </div>
            <div class="property-grid--attr-item ref-align-right">
                <span class="f-16 c-gray-link f-semibold u-hover-opacity-70">Ref: {{$property->ref}}</span>
            </div>       
        </div>
        <div class="">
            <div class="row">
                <div class="col-6">
                    <div class="d-flex align-items-center u-height-100 mb-4 mb-md-0 f-14 f-bold property-price" data-conversion='{!! json_encode($property->ConvertedPrices) !!}'>
                       {!! $property->display_price !!}
                    </div>

                </div>
                <div class="col-6 text-end">
                    <div class="">
                    
                        <a href="{{lang_url($property->url)}}" class="button -secondary f-14 f-xlg-12 -width-full">VIEW DETAILS</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif