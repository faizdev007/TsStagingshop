@php $form_id = "property-alert-form"; @endphp
<div id="property-alert-cta" class="property-alert-cta">
    <div class="property-alert-cta__inner">
        <a @if(!Auth::user() && settings('members_area') == 1) href="{{ url('login?message=Please%20login%20to%20create%20a%20Property%20Alert') }}"
        @else
        href="{{url('account/property-alerts')}}" @endif>
            @if(Auth::user() && settings('members_area') == 1)
                <span class="f-light c-white f-two f-18"><span class="f-italic">Create new</span>&nbsp;<span class="f-bold">Property Alert</span></span>
            @else
                <span class="f-light c-white f-two f-18"><span class="f-italic">{{ trans_fb('app.app_Sign_Up_For', 'Sign Up For') }}</span>&nbsp;<span class="f-bold">{{ trans_fb('app.app_Property_Alerts', 'Property Alerts') }}</span></span>
            @endif
            <span class="c-white f-18 u-inline-block"><i class="fa fa-arrow-right"></i></span>
        </a>
    </div><!-- /.property-alert-cta__inner -->
</div>
<!-- The Modal -->
<div class="modal property-alert-modal fade" id="property-alert-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal">&times;</button>

            <div class="modal-content-inner">
                <div class="text-center">
                    @if(Auth::user() && settings('members_area') == 1)
                        <span class="c-white f-24 f-bold u-block u-mb05">•  Create New Property Alert  •</span>
                    @else
                        <span class="c-white f-24 f-bold u-block u-mb05">•  {{ trans_fb('app.app_Sign_up_for_Property', 'Sign up for Property Alerts') }} •</span>
                    @endif
                </div>
                <form
                    action="{{ url('alert/ajax/add') }}"
                    method="POST"
                    data-toggle="validator"
                    id="ajax-form-{{ $form_id }}"
                >
                    @csrf

                    <div id="response-{{ $form_id }}"></div>

                    <input type="hidden" name="recaptcha_token">
                    <input type="hidden" name="url_from" value="{{ url()->current() }}">

                    <div class="alert-fields">
                        <div class="row">

                            {{-- Full name --}}
                            <div class="col-md-6 col-sm-6 @if(Auth::user()) d-none @endif">
                                <div class="form-group u-mb1">
                                    @if(Auth::user())
                                        <input type="hidden" name="fullname" value="{{ Auth::user()->name }}">
                                    @else
                                        <input
                                            type="text"
                                            name="fullname"
                                            placeholder="{{ trans_fb('app.app_Full_Name', 'Full Name') }} *"
                                            required
                                        >
                                    @endif
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6 col-sm-6 @if(Auth::user()) d-none @endif">
                                <div class="form-group u-mb1">
                                    @if(Auth::user())
                                        <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                                    @else
                                        <input
                                            type="email"
                                            name="email"
                                            placeholder="{{ trans_fb('app.app_Email_Address', 'Email Address') }} *"
                                            required
                                        >
                                    @endif
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>

                            {{-- Telephone --}}
                            <div class="@if(settings('sale_rent') != 'sale_rent') col-md-12 col-sm-12 @else col-md-6 col-sm-6 @endif">
                                <div class="form-group u-mb05">
                                    @if(Auth::user())
                                        <input type="hidden" name="contact" value="{{ Auth::user()->telephone }}">
                                    @else
                                        <input
                                            type="tel"
                                            name="contact"
                                            placeholder="{{ trans_fb('app.app_Telephone', 'Telephone') }}"
                                        >
                                    @endif
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>

                            {{-- Sale / Rent --}}
                            @if(settings('sale_rent') == 'sale_rent')
                                <div class="@if(Auth::user()) col-md-12 @else col-md-6 @endif col-sm-6">
                                    <div class="form-group u-mb05">
                                        <select
                                            name="is_rental"
                                            id="id-status-alert"
                                            class="alert-search-range sale select-pw"
                                        >
                                            @foreach(p_fieldTypes_no_default() as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @elseif(settings('sale_rent') == 'sale')
                                <input type="hidden" name="is_rental" value="0" class="alert-search-range">
                            @else
                                <input type="hidden" name="is_rental" value="1" class="alert-search-range">
                            @endif

                            {{-- Location --}}
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group u-mb1">
                                    <select
                                        name="in"
                                        id="location_list-alert"
                                        class="form-control select-pw-ajax-locations"
                                    >
                                        <option value="">
                                            {{ trans_fb('app.app_Location', 'Location') }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            {{-- Property type --}}
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group u-mb1">
                                    <select name="property_type_id" class="property select-pw">
                                        @foreach(
                                            prepare_dropdown_ptype(
                                                $propertyTypes,
                                                trans_fb('app.app_Property_Type','Property Type')
                                            ) as $key => $value
                                        )
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Beds --}}
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group u-mb1">
                                    <select name="beds" class="beds select-pw">
                                        @foreach(p_beds(trans_fb('app.app_Beds', 'Beds')) as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>

                        {{-- Price slider --}}
                        <div id="alert-search-range" class="li-price price-slider-attr price-range-container-alert">
                            <div class="price-range-container1 price-generic-search">
                                @php
                                    $min_sel = 0;
                                    $max_sel = (!empty($for) && $for == 'rent') ? 40000 : 20000000;
                                @endphp

                                <div class="pr-wrap">
                                    <input
                                        type="hidden"
                                        id="price-range"
                                        class="price-range-input"
                                        name="price_range"
                                        value=""
                                    >

                                    <div
                                        id="slider-range"
                                        class="price-slider"
                                        data-minsel="{{ $min_sel }}"
                                        data-maxsel="{{ $max_sel }}"
                                    ></div>

                                    <div class="price-range-label">
                                        Price Range:
                                        <span class="min-price-slider slider-min formatPrice"></span> -
                                        <span class="max-price-slider slider-max formatPrice"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- GDPR --}}
                        <div class="notes-consent">
                            @include('frontend.' . themeOptions() . '.parts/gdpr')
                        </div>

                        {{-- Submit --}}
                        <div class="property-alert-submit">
                            @if(Auth::user() && settings('members_area') == 1)
                                <button
                                    type="submit"
                                    class="button -tertiary -large text-uppercase u-rounded-4"
                                    id="btn-{{ $form_id }}"
                                >
                                    Save This Alert
                                </button>
                            @else
                                <button
                                    type="submit"
                                    class="button -tertiary -large text-uppercase u-rounded-4"
                                    id="btn-{{ $form_id }}"
                                >
                                    Sign Up Now
                                </button>
                            @endif
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
