@if(count($properties))
<div class="property-grid--item u-item-shadowed--style-1 position-relative">
    @if ($property->property_status)
        <div class="property-grid--featured-ribbon">{{$property->property_status}}</div>
    @endif
    <div class="property-grid-thumb--slick">
@if( count($property->propertySearchMediaPhotos) )
    @php $first = true; @endphp
    @foreach($property->propertySearchMediaPhotos as $media)       
        <div class="property-grid--image">
            <div class="property-grid--image-inner u-height-246 go-center fill">
               
                <a href="{{lang_url($property->url)}}">
                    <img class="b-lazy property-grid--image-item" src="{{ blankImg() }}" data-src="{{ $property->primary_photo }}" alt="{{$property->image_alt}} photo">
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
                        <img class="b-lazy" src="{{ blankImg() }}" data-src="{{ default_thumbnail() }}" alt="{{$property->image_alt}}">
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
       
        <div class="c-gray mb-3 u-min-height-24">
            <a class="f-13 c-gray-link  u-hover-opacity-70">{{ $property->DisplayPropertyAddress }}</a>
        </div>
        
        <div class="">
            <div class="row">
                <div class="col-6">
                    <div class="d-flex align-items-center u-height-100 mb-4 mb-md-0 f-14 f-bold">
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
