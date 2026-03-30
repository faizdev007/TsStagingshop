@php
$form_id = "valuation-form";
@endphp

<form
    action="{{ url('ajax/valuation') }}"
    method="POST"
    data-toggle="validator"
    id="ajax-form-{{ $form_id }}"
>
    @csrf

    <div class="property-enquiry">

        <div id="response-{{ $form_id }}"></div>
        <input type="hidden" name="recaptcha_token">

        {{-- Property location --}}
        <div class="valuation-item">
            <h3>Your property location</h3>

            <div class="row">

                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <input
                            type="text"
                            name="Houseno"
                            placeholder="{{ trans_fb('app.app_House no', 'House no') }} *"
                            required
                        >
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <input
                            type="text"
                            name="Streetname"
                            placeholder="{{ trans_fb('app.app_Street name', 'Street name') }} *"
                            required
                        >
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <input
                            type="text"
                            name="location"
                            placeholder="{{ trans_fb('app.app_Address', 'Address') }} *"
                            required
                        >
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <input
                            type="text"
                            name="town"
                            placeholder="{{ trans_fb('app.app_town', 'town') }} *"
                            required
                        >
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <input
                            type="text"
                            name="County"
                            placeholder="{{ trans_fb('app.app_County', 'County') }} *"
                            required
                        >
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <input
                            type="text"
                            name="Postcode"
                            placeholder="{{ trans_fb('app.app_Postcode', 'Postcode') }} *"
                            required
                        >
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>

            </div>
        </div>

        {{-- About property --}}
        <div class="valuation-item">
            <h3>About your property</h3>

            <div class="row">

                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <select
                            class="w-100 type-select"
                            name="property_type"
                            data-placeholder="Property type *"
                        >
                            @php
                                $propertyTypesArray = prepare_dropdown_ptype_slug($propertyTypes ?? [], 'Property Type');
                            @endphp

                            @foreach($propertyTypesArray as $key => $ptype)
                                <option value="{{ $key }}">{{ $ptype }}</option>
                            @endforeach
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>

                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <select
                            class="w-100 type-select"
                            name="liketodo"
                            data-placeholder="What would you like to do? *"
                        >
                            <option value="">What would you like to do? *</option>
                            <option value="a">AAA</option>
                            <option value="b">BBB</option>
                            <option value="c">CCC</option>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>

            </div>
        </div>

        {{-- Your details --}}
        <div class="valuation-item">
            <h3>Your details</h3>

            <div class="row">

                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <input
                            type="text"
                            name="fullname"
                            placeholder="{{ trans_fb('app.app_Full Name', 'Full Name') }} *"
                            required
                        >
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>

                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <input
                            type="email"
                            name="email"
                            placeholder="{{ trans_fb('app.app_Email Address', 'Email Address') }} *"
                            required
                        >
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>

                <div class="col-md-12 col-sm-12">
                    <div class="form-group">
                        <input
                            type="tel"
                            name="telephone"
                            class="phone-flags"
                            placeholder="{{ trans_fb('app.app_Telephone Number', 'Telephone Number') }} *"
                            required
                        >
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>

            </div>

            <div class="form-group">
                <textarea
                    name="message"
                    rows="2"
                    cols="50"
                    placeholder="{{ trans_fb('app.app_Message', 'Message (optional)') }}"
                ></textarea>
                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
            </div>

            {{-- reCAPTCHA --}}
            <div
                id="gr-{{ $form_id }}"
                class="g-recaptcha-pw"
                data-sitekeypw="{{ settings('recaptcha_public_key') }}"
                data-sizepw="invisible"
                data-callbackpw="valuationSubmit"
                data-counterpw=""
            ></div>

            {{-- Submit --}}
            <div class="valution-btn">
                <button
                    type="submit"
                    id="btn-{{ $form_id }}"
                    class="button -secondry -tertiary -large f-16 f-two"
                    data-animate="pulse"
                >
                    {{ trans_fb('app.app_Submit Enquiry', 'REQUEST A VALUATION') }}
                </button>
            </div>

        </div>
    </div>
</form>
<script type="text/javascript">
    function valuationSubmit(response) {
        $("#ajax-form-<?=$form_id?>").find('input[name="recaptcha_token"]').val(response);
        $("#ajax-form-<?=$form_id?>").trigger('submit');
    }
</script>
