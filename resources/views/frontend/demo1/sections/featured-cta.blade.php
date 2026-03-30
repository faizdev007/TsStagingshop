
<section class="c-bg-properties">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-3">
                <a class="fc-item" href="{{lang_url('property-for-sale')}}">
                    <span class="fc-item__icon -house u-block u-mb1 u-center"></span>
                    <span class="f-20 f-two f-bold u-block u-mb05 c-dark">{{ trans_fb('app.app_Search', 'Search') }}</span>
                    <p class="f-14 fc-item__desc">{{ trans_fb('app.app_Find_Perfect_Property', 'Find your perfect property') }}</p>
                </a>
            </div>
            <div class="col-md-3 col-sm-3">
                <a class="fc-item" href="{{lang_url('sell')}}">
                    <span class="fc-item__icon -keys u-block u-mb1 u-center"></span>
                    <span class="f-20 f-two f-bold u-block u-mb05 c-dark">{{ trans_fb('app.app_Sell', 'Sell') }}</span>
                    <p class="f-14 fc-item__desc">{{ trans_fb('app.app_SELL_YOUR_PROPERTY_W', 'Sell your property with us') }}</p>
                </a>
            </div>
            <div class="col-md-3 col-sm-3">
                <a class="fc-item" href="{{lang_url('valuation')}}">
                    <span class="fc-item__icon -plans u-block u-mb1 u-center"></span>
                    <span class="f-20 f-two f-bold u-block u-mb05 c-dark">{{ trans_fb('app.app_Valuation', 'Valuation') }}</span>
                    <p class="f-14 fc-item__desc">{{ trans_fb('app.app_Free_Valuation', 'Get a free Valuation for your property') }}</p>
                </a>
            </div>
            <div class="col-md-3 col-sm-3">
                @if(settings('team_page'))
                    <a class="fc-item" href="{{url('team')}}">
                        <span class="fc-item__icon -people u-block u-mb1 u-center"></span>
                        <span class="f-20 f-two f-bold u-block u-mb05 c-dark">{{ trans_fb('app.app_Our_Team', 'Our Team') }}</span>
                        <p class="f-14 fc-item__desc">{{ trans_fb('app.app_Our_Team_More', 'Meet our team and find out more') }}</p>
                    </a>
                @else
                    <a class="fc-item" href="{{url('about-us')}}">
                        <span class="fc-item__icon -people u-block u-mb1 u-center"></span>
                        <span class="f-20 f-two f-bold u-block u-mb05 c-dark">{{ trans_fb('app.app_About_Us', 'About Us') }}</span>
                        <p class="f-14 fc-item__desc">Find out more about us</p>
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
