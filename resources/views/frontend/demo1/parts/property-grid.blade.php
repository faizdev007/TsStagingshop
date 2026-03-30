<div class="property-grid">
    <div class="-image-box">
        <img class="b-lazy" src="{{ blankImg() }}" data-src="{{ $property->PrimaryPhoto }}" alt="{{$property->image_alt}}">
            @if ($property->property_status)
                <div class="-hb-status {{ ($property->property_status=='Available')?'-blue':'' }}"><span>{{ $property->property_status }}</span></div>
            @endif
        <div class="-hb-shortlist">
            <a href="#"
            class="-shortlist shortlist shortlist-add {{ ($property->CheckShortlistIp) ? 'shortlist-confirm-action' : '' }}"
            data-url="{{ url('shortlist/ajax/add') }}"
            data-property-id="{{ $property->id }}"
            data-save-text=""
            data-remove-text=""
            >
                 {!! ($property->CheckShortlistIp) ? '<i class="fas fa-times"></i>' : '<i class="fas fa-heart"></i>' !!}
            </a>
        </div>
    </div>
     <div class="-hover-box pw-aligner">
        <div class="-hb-wrap">
            <div class="-hb-price-status">{!! $property->display_price !!}</div>
            <div class="-hb-title">{{ $property->search_headline }}
                @if(0)
                <?php
                    $categories = $property->categories;
                    foreach($property->categories as $cat){
                        echo '<br>';
                        if($cat->slug == 'marina' || $cat->slug =='beachfront'){
                            echo $cat->slug;
                        }
                    }
                ?>
                @endif
            </div>
            @if($property->DisplayPropertyAddress)
            <div class="-hb-address"><i class="fas fa-map-marker-alt"></i> {{ $property->DisplayPropertyAddress }}</div>@endif
            <div class="-hb-attr text-uppercase">{!! $property->BedBathArea !!}</div>
            <div class="-hb-cta">
                <a href="{{ lang_url($property->url)}}">VIEW DETAILS</a>
            </div>
        </div>
    </div>
</div>
