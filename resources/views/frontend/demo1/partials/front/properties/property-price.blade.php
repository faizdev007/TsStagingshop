<style>
.dropdown-item.active, .dropdown-item:active{
    background-color: #d9b483;
    color: #fff!important;
}
</style>
<ul>
    <li>
        <h5 class="-js-price-display f-15 property-price" data-conversion='{!! json_encode($property->ConvertedPrices) !!}'>{!! $property->display_price !!}</h5>
    </li>
    <li class="-currency f-15">

        @php $all_currencies = all_currencies(); @endphp

        <div class="search-form-home--item -country px-0">
            <a class="nav-link dropdown-toggle border px-3" href="#" id="navbarDropdownCurrency" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-current="{{ !empty($all_currencies[get_current_currency()])? get_current_currency() :'Currency' }}">
                <!-- GBP (£)  -->
                {{ !empty($all_currencies[get_current_currency()])? get_current_currency() :'Currency' }}
                <span class="pw-nav-hover"><span class="pw-nav-hover-inner"></span></span>
            </a>
            <div class="dropdown-menu w-auto" aria-labelledby="navbarDropdownCurrency">
                @foreach($all_currencies as $currency => $symbol)
                @if( get_current_currency() != $currency)
                <a href="{{lang_url('properties/set/currency/'.$currency)}}" rel="nofollow" class="dropdown-item hover-tick">
                    {{$currency}}
                </a>
                @endif
                @endforeach
            </div>

        </div>
    </li>

    <!-- old currency dropdown listting   -->
    <!-- <li class="-currency d-none f-15">
        <div class="property-currency-item -country">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownCurrency" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                <div class="currency-current"> {{ !empty($all_currencies[get_current_currency()])? get_current_currency() :'Currency' }}
                </div>
                <span class="pw-nav-hover"><span class="pw-nav-hover-inner"></span></span>
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownCurrency">
                <a href="#" class="-active aed -currency-js-change" data-currency="aed" data-price="{!! strip_tags($property->DisplayPriceAED) !!}">AED</a>
                <a href="#" class="usd -currency-js-change" data-currency="usd" data-price="{!! strip_tags($property->DisplayPriceUSD) !!}">USD</a>
                <a href="#" class="gbp -currency-js-change" data-currency="gbp" data-price="{!! strip_tags($property->DisplayPriceGBP) !!}">GBP</a>
                <a href="#" class="eur -currency-js-change" data-currency="eur" data-price="{!! strip_tags($property->DisplayPriceEUR) !!}">EUR</a>
                <a href="#" class="czk -currency-js-change" data-currency="czk" data-price="{!! strip_tags($property->DisplayPriceCZK) !!}">CZK</a>

                @if(0)
                <a href="#" class="-active gbp -currency-js-change" data-currency="gbp" data-price="{!! strip_tags($property->display_price) !!}">&pound;</a>
                <a href="#" class="usd -currency-js-change" data-currency="usd" data-price="{!! strip_tags($property->DisplayPriceUSD) !!}">&dollar;</a>
                @endif
            </div>

        </div>
    </li> -->

</ul>