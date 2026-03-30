
@extends('backend.layouts.master')

@section('admin-content')

<form
    action="{{ route('testimonials.update', $testimonial->id) }}"
    method="POST"
>
    @csrf
    @method('PUT')

    <div class="row current_url" data-url="{{ admin_url('testimonials') }}">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel pw">

                <div class="x_title">
                    <h2></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li class="top-button">
                            <a href="{{ admin_url('testimonials') }}" class="btn btn-small btn-primary">
                                View Testimonials
                            </a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">

                    {{-- Basic fields --}}
                    <div class="x_panel pw-inner-tabs">
                        <div class="x_title">
                            <h2>Testimonials Fields</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content pw-open">
                            <div class="xpw-fields">
                                <div class="row">

                                    <div class="col-md-3">
                                        <div class="control-form">
                                            <label for="name">Name: {!! required_label() !!}</label>
                                            <input
                                                type="text"
                                                name="name"
                                                id="name"
                                                class="form-control"
                                                placeholder="Please enter..."
                                                value="{{ old('name', $testimonial->name) }}"
                                            >
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="control-form">
                                            <label for="location">Location: {!! required_label() !!}</label>
                                            <input
                                                type="text"
                                                name="location"
                                                id="location"
                                                class="form-control"
                                                placeholder="Please enter..."
                                                value="{{ old('location', $testimonial->location) }}"
                                            >
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="control-form">
                                            <label for="date">Date: {!! required_label() !!}</label>
                                            <input
                                                type="text"
                                                name="date"
                                                id="date"
                                                class="form-control pw-datepicker"
                                                placeholder="Please select..."
                                                autocomplete="off"
                                                value="{{ old('date', $testimonial->date) }}"
                                            >
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="control-form">
                                            <label for="rating">Rating: {!! required_label() !!}</label>
                                            <input
                                                type="text"
                                                name="rating"
                                                id="rating"
                                                class="form-control"
                                                placeholder="Enter a number from 0 to 5"
                                                autocomplete="off"
                                                value="{{ old('rating', $testimonial->rating) }}"
                                            >
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- English quote --}}
                    <div class="x_panel pw-inner-tabs">
                        <div class="x_title">
                            <h2>English</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content pw-open">
                            <div class="xpw-fields">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="control-form">
                                            <label for="quote">Quote: {!! required_label() !!}</label>
                                            <textarea
                                                name="quote"
                                                id="quote"
                                                class="style-1 mceEditor"
                                                style="width:100%"
                                                placeholder="Please enter..."
                                                maxlength="6000"
                                            >{{ old('quote', $testimonial->quote) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Translations --}}
                    @if(settings('translations') && isset($languages))
                        @php
                            $translationData = [];
                            foreach ($testimonial->translations as $t) {
                                $translationData[$t->language] = $t->quote;
                            }
                        @endphp

                        @foreach($languages as $label => $lang)
                            <div class="x_panel pw-inner-tabs">
                                <div class="x_title">
                                    <h2>{{ $label }}</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a></li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="x_content pw-open">
                                    <div class="xpw-fields">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label>Quote:</label>
                                                <textarea
                                                    name="quote_{{ $lang }}"
                                                    id="quote{{ $lang }}"
                                                    class="mceEditor content"
                                                    style="width:100%"
                                                    placeholder="Please enter..."
                                                    maxlength="60000"
                                                >{{ old('quote_'.$lang, $translationData[$lang] ?? '') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                </div>
            </div>
        </div>
    </div>

    <div class="form-group sticky-buttons">
        <button type="submit" class="btn btn-large btn-primary">
            Save
        </button>

        <a href="{{ admin_url('testimonials') }}" class="btn btn-default btn-spacing">
            Cancel <span>and Return</span>
        </a>

        <a
            href="#"
            class="btn btn-danger btn-spacing modal-toggle"
            data-item-id="{{ $testimonial->id }}"
            data-toggle="modal"
            data-modal-type="delete"
            data-modal-title="Delete Testimonial"
            data-modal-size="small"
            data-delete-type="testimonials"
            data-target="#global-modal"
        >
            <i class="fas fa-trash"></i> Delete
        </a>
    </div>
</form>

<script>
document.getElementById('rating').addEventListener('input', function() {
    let val = this.value;

    // Allow only numbers and one dot
    val = val.replace(/[^0-9.]/g, '');

    // Prevent more than one decimal point
    if ((val.match(/\./g) || []).length > 1) {
        val = val.substring(0, val.lastIndexOf('.'));
    }

    // Limit to one digit after the decimal
    if (val.includes('.')) {
        const [intPart, decPart] = val.split('.');
        val = intPart + '.' + decPart.substring(0, 1);
    }

    // Convert to number and apply range restriction
    let numVal = parseFloat(val);
    if (numVal > 5) val = 5;
    if (numVal < 0) val = 0;

    this.value = val;
});
</script>

@endsection

@push('headerscripts')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endpush

@push('footerscripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('assets/admin/build/js/pw-testimonials.js')}}"></script>
@endpush
