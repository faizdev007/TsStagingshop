 @php
    $ctaID = !empty($ctaID) ? $ctaID : 1;
    $ctaTitle = !empty($ctaTitle) ? $ctaTitle : 'Find My Dream Home';
    $form_id = "home-cta-".$ctaID."-form";
@endphp
<form
    action="{{ url('ajax/home-cta-' . $ctaID) }}"
    method="POST"
    data-toggle="validator"
    id="ajax-form-{{ $form_id }}"
>
    @csrf

    <h3 class="-modal-title f-35 f-two text-center">
        {{ $ctaTitle }}
        <span class="-border-title-generic"></span>
    </h3>

    <div id="response-{{ $form_id }}"></div>

    <input type="hidden" name="recaptcha_token" id="recaptcha_token">

    <div class="-modal-fields select-style-1 -dark -light-border">
        <div class="row">

            {{-- First name --}}
            <div class="col-sm-6 col-6">
                <div class="form-group form-group-1 -transparent-bg u-mb1">
                    <input
                        type="text"
                        name="firstname"
                        placeholder="FIRST NAME *"
                        value="{{ settings('members_area') == '1' && Auth::check() ? Auth::user()->firstname : '' }}"
                        required
                    >
                    <span class="form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>

            {{-- Last name --}}
            <div class="col-sm-6 col-6">
                <div class="form-group form-group-1 -transparent-bg u-mb1">
                    <input
                        type="text"
                        name="lastname"
                        placeholder="LAST NAME *"
                        value="{{ settings('members_area') == '1' && Auth::check() ? Auth::user()->lastname : '' }}"
                        required
                    >
                    <span class="form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>

            {{-- Email --}}
            <div class="col-sm-6 col-6">
                <div class="form-group form-group-1 -transparent-bg u-mb1">
                    <input
                        type="email"
                        name="email"
                        placeholder="EMAIL ADDRESS *"
                        value="{{ settings('members_area') == '1' && Auth::check() ? Auth::user()->email : '' }}"
                        @if(settings('members_area') == '1' && Auth::check()) readonly @endif
                        required
                    >
                    <span class="form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>

            {{-- Telephone --}}
            <div class="col-sm-6 col-6">
                <div class="form-group form-group-1 -transparent-bg u-mb1">
                    <input
                        type="tel"
                        name="telephone"
                        placeholder="TELEPHONE NUMBER *"
                        value="{{ settings('members_area') == '1' && Auth::check() ? Auth::user()->telephone : '' }}"
                        @if(settings('members_area') == '1' && Auth::check()) readonly @endif
                        required
                    >
                    <span class="form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>

            {{-- Address --}}
            <div class="col-sm-12 col-12">
                <div class="form-group form-group-1 -transparent-bg u-mb1">
                    <input
                        type="text"
                        name="address"
                        placeholder="ADDRESS *"
                    >
                </div>
            </div>

            {{-- City --}}
            <div class="col-sm-6 col-6">
                <div class="form-group form-group-1 -transparent-bg u-mb1">
                    <input
                        type="text"
                        name="city"
                        placeholder="TOWN/CITY"
                    >
                </div>
            </div>

            {{-- State --}}
            <div class="col-sm-6 col-6">
                <div class="form-group form-group-1 -transparent-bg u-mb1">
                    <input
                        type="text"
                        name="state"
                        placeholder="COUNTY"
                    >
                </div>
            </div>

            {{-- Country --}}
            <div class="col-sm-6 col-6">
                <div class="form-group form-group-1 -transparent-bg u-mb1">
                    <select name="country" class="property select-pw">
                        @foreach(p_countries_name('COUNTRY') as $key => $value)
                            <option
                                value="{{ $key }}"
                                {{ $value === 'United Kingdom' ? 'selected' : '' }}
                            >
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Property type --}}
            <div class="col-sm-6 col-6">
                <div class="form-group form-group-1 -transparent-bg u-mb1">
                    <select name="property_type" class="property select-pw">
                        @foreach(prepare_dropdown_ptype_slug($propertyTypes, 'PROPERTY TYPE') as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Beds --}}
            <div class="col-sm-6 col-6">
                <div class="form-group form-group-1 -transparent-bg u-mb1">
                    <select name="beds" class="beds select-pw">
                        @foreach(p_beds_frontend('MINIMUM BEDROOMS') as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Baths --}}
            <div class="col-sm-6 col-6">
                <div class="form-group form-group-1 -transparent-bg u-mb1">
                    <select name="baths" class="baths select-pw">
                        @foreach(p_baths('MINIMUM BATHROOMS') as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Conditional fields --}}
            @if($ctaID == 1 || $ctaID == 3)

                <div class="col-sm-12 col-12">
                    <div class="form-group form-group-1 -transparent-bg u-mb1">
                        <textarea
                            name="message"
                            placeholder="MY DREAM HOME WOULD IDEALLY BE..."
                        ></textarea>
                    </div>
                </div>

            @else

                <div class="col-sm-6 col-6">
                    <div class="form-group form-group-1 -transparent-bg u-mb1">
                        <select name="parking" class="parking select-pw">
                            <option value="">PARKING</option>
                            <option value="yes">YES</option>
                            <option value="no">NO</option>
                        </select>
                    </div>
                </div>

                <div class="col-sm-6 col-6">
                    <div class="form-group form-group-1 -transparent-bg u-mb1">
                        <select name="grage" class="Garage select-pw">
                            <option value="">GARAGE</option>
                            <option value="yes">YES</option>
                            <option value="no">NO</option>
                        </select>
                    </div>
                </div>

                <div class="col-sm-12 col-12">
                    <div class="form-group form-group-1 -transparent-bg u-mb1">
                        <textarea
                            name="message"
                            placeholder="PLEASE TELL US ABOUT YOUR PROPERTY…"
                        ></textarea>
                    </div>
                </div>

                <div class="col-sm-12 col-12">
                    <div class="form-group form-group-1 -transparent-bg u-mb1">
                        <textarea
                            name="valuation_message"
                            placeholder="WHY ARE YOU LOOKING FOR A VALUATION?"
                        ></textarea>
                    </div>
                </div>

            @endif

            {{-- GDPR --}}
            <div class="col-sm-12 col-12">
                <div class="text-consent f-12 c-gray-26 u-mt05 text-center">
                    @include('frontend.' . themeOptions() . '.parts/gdpr')
                </div>
            </div>

            {{-- Submit --}}
            <div class="col-sm-12 col-12">
                <div class="form-group-1 -submit-btn text-center u-mt3">
                    <button
                        type="submit"
                        id="btn-{{ $form_id }}"
                        name="button"
                        class="button -secondary f-700 f-14 -x-large"
                    >
                        SUBMIT
                    </button>
                </div>
            </div>

        </div>
    </div>

    @if(isset($type))
        <input type="hidden" name="submission_type" value="{{ $type }}">
    @endif
</form>

