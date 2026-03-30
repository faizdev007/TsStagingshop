    <!-- Property Inquiry Modal -->
    <!-- Modal -->
    <div class="modal fade" id="enquerymodalmain" tabindex="-1" aria-labelledby="enquerymodalmainLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <button 
                    type="button" 
                    id="btnclose" 
                    class="btn-close position-absolute end-0 p-3"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                    style="z-index:10;">
                </button>
                <div class="modal-body" id="propertyInquiryModal">
                    <!-- Logo -->
                    <div style="text-align: center; margin-bottom: 10px;">
                        <img src="{{ asset('assets/'.themeOptions().'/images/logo.svg') }}" alt="Logo" style="max-width: 200px;">
                    </div>

                    <!-- Headings -->
                    <h3 style="text-align: center; margin-bottom: 5px; font-weight: 600; color: #333; font-size: 18px;">Can't find what you are looking for?</h3>
                    <p style="text-align: center; margin-bottom: 10px; color: #666; font-size: 13px;">Let us know, and we'll assist you with your search.</p>

                    <div class="">
                        <!-- Form -->
                        <form
                            id="modal-inquiry-form"
                            method="POST"
                            action="{{ url('ajax/property-inquiry-form') }}"
                            data-toggle="validator"
                        >
                            @csrf

                            <div id="form-response-message"></div>

                            <input type="hidden" name="recaptcha_token" id="recaptcha_token">

                            <div class="row">
                                {{-- First Name --}}
                                <div class="col-md-6">
                                    <div class="form-group form-group-1 -transparent-bg u-mb1">
                                        <input
                                            class="form-control"
                                            type="text"
                                            name="fullname"
                                            placeholder="First Name"
                                            required
                                            autocomplete="off"
                                        >
                                        <span class="form-control-feedback" aria-hidden="true"></span>
                                    </div>
                                </div>

                                {{-- Last Name --}}
                                <div class="col-md-6">
                                    <div class="form-group form-group-1 -transparent-bg u-mb1">
                                        <input
                                            class="form-control"
                                            type="text"
                                            name="lastname"
                                            placeholder="Last Name"
                                            required
                                            autocomplete="off"
                                        >
                                        <span class="form-control-feedback" aria-hidden="true"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                {{-- Country --}}
                                <div class="col-md-6">
                                    <div class="form-group form-group-1 -transparent-bg u-mb1">
                                        <select
                                            name="country"
                                            id="modal_location_list"
                                            class="form-control modal-select"
                                            required
                                            autocomplete="off"
                                        >
                                            @foreach(get_locations() as $key => $value)
                                                <option
                                                    value="{{ $key }}"
                                                    {{ $key === 'Dubai, United Arab Emirates' ? 'selected' : '' }}
                                                >
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="form-control-feedback" aria-hidden="true"></span>
                                    </div>
                                </div>

                                {{-- Area --}}
                                <div class="col-md-6">
                                    <div class="form-group form-group-1 -transparent-bg u-mb1">
                                        <select
                                            name="area"
                                            id="modal_area_list"
                                            class="form-control modal-select"
                                            required
                                            autocomplete="off"
                                        >
                                        @foreach(get_areas('AREA', 'Dubai, United Arab Emirates') as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                        </select>
                                        <span class="form-control-feedback" aria-hidden="true"></span>
                                    </div>
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="form-group form-group-1 -transparent-bg u-mb1">
                                <input
                                    class="form-control"
                                    type="email"
                                    name="email"
                                    placeholder="Email Address"
                                    required
                                    autocomplete="off"
                                >
                                <span class="form-control-feedback" aria-hidden="true"></span>
                            </div>

                            {{-- Telephone --}}
                            <div class="form-group form-group-1 -transparent-bg u-mb1">
                                <input
                                    class="form-control telephone-input"
                                    type="tel"
                                    name="telephone"
                                    id="telephone-input"
                                    placeholder="Telephone"
                                    pattern="^\+[0-9]+"
                                    required
                                    autocomplete="off"
                                >
                                <span class="form-control-feedback" aria-hidden="true"></span>

                                <div
                                    id="phone-warning"
                                    class="text-danger"
                                    style="display:none; color:red; margin-top:5px;"
                                >
                                    Invalid phone number format. Please select the country code
                                </div>
                            </div>

                            {{-- Message --}}
                            <div class="form-group form-group-1 -transparent-bg">
                                <textarea
                                    class="form-control"
                                    name="message"
                                    placeholder="Message"
                                    rows="4"
                                    autocomplete="off"
                                ></textarea>
                                <span class="form-control-feedback" aria-hidden="true"></span>
                            </div>

                            {{-- Terms --}}
                            <div class="form-group">
                                <div class="customcheck">
                                    <input
                                        class="styled-checkbox"
                                        id="modal-styled-checkbox-1"
                                        name="terms"
                                        type="checkbox"
                                        value="yes"
                                        checked
                                        required
                                        autocomplete="off"
                                    >
                                    <label for="modal-styled-checkbox-1">
                                        I agree to the
                                        <a href="{{ url('terms-conditions') }}">Terms of Use</a>
                                        &amp;
                                        <a href="{{ url('privacy-policy') }}">Privacy Policy</a>.
                                    </label>
                                </div>
                            </div>

                            {{-- Newsletter --}}
                            <div class="form-group">
                                <div class="customcheck">
                                    <input
                                        class="styled-checkbox"
                                        id="modal-styled-checkbox-2"
                                        type="checkbox"
                                        name="newsletter"
                                        value="yes"
                                        checked
                                        autocomplete="off"
                                    >
                                    <label for="modal-styled-checkbox-2">
                                        Subscribe to the monthly newsletter
                                    </label>
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="form-group form-group-1 text-center">
                                <button
                                    type="submit"
                                    id="btn-submit-inquiry"
                                    class="btn btn-main-sumbit custom-btn-submit"
                                >
                                    Email Enquiry
                                </button>
                            </div>
                        </form>
        
                        <!-- Call and WhatsApp Buttons -->
                        <div class="mt-3">
                            <div class="custom-btn-container p-0">
                                <a href="tel:+971585365111" class="btn custom-btn" style="margin-top:0px!important;"><i class="fas fa-phone">&nbsp;</i>Call</a>
                                <button class="btn whatsapp-btn" style="margin-top:0px!important;" onclick="sendWhatsAppFromModal();"><i class="fab fa-whatsapp">&nbsp;</i>WhatsApp</button>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('insertClick') }}" id="whatsapp-click-form">
                        @csrf
                        <input type="hidden" name="h1value" value="Inquiry">
                        <input type="hidden" name="h2value" value="Property Inquiry">
                        <button type="submit" id="whatsappClickButton" name="button_name" value="WhatsAppButton" style="display: none;">Click me</button>
                    </form>
                </div>
                <!-- </div> -->
            </div>
        </div>
    </div>

    <div id="" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; overflow: hidden; background-color: rgba(0,0,0,0.4);">
    <!-- propertyInquiryModal -->
    </div>

    <!-- Modal Form Styles -->
    <style>
        #propertyInquiryModal .form-control {
            padding: 8px 12px;
            margin-bottom: 8px;
            border-radius: 0;
            border: 1px solid #ddd;
            font-size: 13px;
        }

        /* Modal Select2 Styling */
        #propertyInquiryModal .modal-select {
            height: auto;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg fill="%23333" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/></svg>');
            background-repeat: no-repeat;
            background-position: right 10px center;
        }

        /* Custom Select2 Styling for Modal Only */
        #propertyInquiryModal .select2-container--default .select2-selection--single {
            height: auto;
            padding: 12px 15px;
            border-radius: 0;
            border: 1px solid #ddd;
            font-size: 14px;
            margin-top: 0;
        }

        #propertyInquiryModal .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: normal;
            padding: 0;
            color: #333;
        }

        #propertyInquiryModal .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
            top: 0;
            right: 10px;
        }

        /* Telephone Input Spacing */
        #propertyInquiryModal .telephone-input {
            padding-left: 50px !important;
            /* Add space for country flag and code */
        }

        /* Fix for intl-tel-input plugin */
        #propertyInquiryModal .iti {
            width: 100%;
        }

        #propertyInquiryModal .iti__flag-container {
            display: flex;
            align-items: center;
        }

        #propertyInquiryModal .iti__selected-flag {
            padding: 0 12px;
        }

        #propertyInquiryModal .btn-main-sumbit {
            padding: 8px 15px;
            display: block;
            width: 100%;
            border-radius: 0;
            background: #ffffff;
            border-color: #d9b483;
            color: #d9b483;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        #propertyInquiryModal .btn-main-sumbit:hover {
            border-color: #d9b483;
            background-color: #d9b483;
            color: white;
        }

        #propertyInquiryModal .styled-checkbox {
            position: absolute;
            opacity: 0;
        }

        #propertyInquiryModal .styled-checkbox+label {
            position: relative;
            cursor: pointer;
            padding: 0;
            padding-left: 25px;
            font-size: 14px;
        }

        #propertyInquiryModal .styled-checkbox+label:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 18px;
            height: 18px;
            border: 1px solid #ddd;
            background: #fff;
        }

        #propertyInquiryModal .styled-checkbox:checked+label:after {
            content: '✓';
            position: absolute;
            left: 4px;
            top: -1px;
            color: #d9b483;
            font-size: 14px;
        }

        /* Modal positioning fixes */
        #propertyInquiryModal>div {
            /* transform: translate(-50%, -50%); */
            margin-top: 10px !important;
        }

        /* Mobile responsive styles */
        @media (max-width: 767px) {
            #propertyInquiryModal>div {
                transform: none !important;
                top: 0 !important;
                left: 0 !important;
                margin: 0 !important;
                max-height: 100% !important;
                height: 100% !important;
                width: 100% !important;
                border-radius: 0 !important;
                overflow-y: auto;
                padding: 15px !important;
            }

            #propertyInquiryModal .form-control {
                padding: 6px 8px;
                margin-bottom: 5px;
                font-size: 12px;
                height: auto;
            }

            #propertyInquiryModal .row {
                margin-left: -5px;
                margin-right: -5px;
            }

            #propertyInquiryModal .col-md-6 {
                padding-left: 5px;
                padding-right: 5px;
            }

            #propertyInquiryModal h3 {
                font-size: 16px;
                margin-bottom: 3px;
                margin-top: 5px;
            }

            #propertyInquiryModal p {
                font-size: 12px;
                margin-bottom: 8px;
            }

            #propertyInquiryModal .btn-main-sumbit,
            #propertyInquiryModal .custom-btn,
            #propertyInquiryModal .whatsapp-btn {
                padding: 8px 10px;
                font-size: 13px;
                margin-top: 3px;
            }

            #propertyInquiryModal .styled-checkbox+label {
                font-size: 12px;
            }

            /* #propertyInquiryModal img {
                max-width: 120px !important;
            } */
        }

        /* Call and WhatsApp Buttons Styles */
        #propertyInquiryModal .custom-btn-container {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        #propertyInquiryModal .custom-btn {
            width: 48%;
            background-color: #d9b483;
            color: white;
            font-weight: 600;
            font-size: 13px;
            font-family: 'Work Sans', sans-serif;
            border-radius: 0;
            padding: 8px 10px;
            transition: border-color 0.3s;
            border-color: #d9b483;
        }

        #propertyInquiryModal .whatsapp-btn {
            width: 48%;
            background-color: #68c665;
            color: white;
            font-weight: 600;
            font-size: 13px;
            font-family: 'Work Sans', sans-serif;
            border-radius: 0;
            padding: 8px 10px;
            transition: border-color 0.3s;
            border-color: #68c665;
        }

        #propertyInquiryModal .whatsapp-btn:hover {
            background-color: white;
            color: #68c665;
            border-color: #68c665;
        }

        #propertyInquiryModal .custom-btn:hover {
            border-color: #d9b483;
            background-color: white;
            color: #d9b483;
        }
    </style>