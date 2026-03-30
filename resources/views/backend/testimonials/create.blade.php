@extends('backend.layouts.master')

@section('admin-content')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel pw">
            <div class="x_title">
                <h2><br/></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="x_panel pw-inner-tabs">
                    <div class="x_title">
                        <h2>Testimonials Fields</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content pw-open">

                    	<form
                            action="{{ route('testimonials.store') }}"
                            method="POST"
                        >
                            @csrf

                            <div class="xpw-fields">
                                <div class="row">

                                    <div class="col-md-3">
                                        <div class="control-form">
                                            <label for="name">
                                                Name: {!! required_label() !!}
                                            </label>
                                            <input
                                                type="text"
                                                name="name"
                                                id="name"
                                                class="form-control"
                                                placeholder="Please enter..."
                                                value="{{ old('name') }}"
                                            >
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="control-form">
                                            <label for="location">
                                                Location: {!! required_label() !!}
                                            </label>
                                            <input
                                                type="text"
                                                name="location"
                                                id="location"
                                                class="form-control"
                                                placeholder="Please enter..."
                                                value="{{ old('location') }}"
                                            >
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="control-form">
                                            <label for="date">
                                                Date: {!! required_label() !!}
                                            </label>
                                            <input
                                                type="text"
                                                name="date"
                                                id="date"
                                                class="form-control pw-datepicker"
                                                placeholder="Please select..."
                                                autocomplete="off"
                                                value="{{ old('date') }}"
                                            >
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="control-form">
                                            <label for="rating">
                                                Rating: {!! required_label() !!}
                                            </label>
                                            <input
                                                type="text"
                                                name="rating"
                                                id="rating"
                                                class="form-control"
                                                placeholder="Please enter between 0 to 5"
                                                autocomplete="off"
                                                value="{{ old('rating') }}"
                                            >
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="control-form">
                                            <label for="quote">
                                                Quote: {!! required_label() !!}
                                            </label>
                                            <textarea
                                                name="quote"
                                                id="quote"
                                                class="tagEditor style-1 mceEditor"
                                                style="width:100%"
                                                placeholder="Please enter..."
                                                maxlength="6000"
                                            >{{ old('quote') }}</textarea>
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group sticky-buttons">
                                    <button
                                        type="submit"
                                        class="btn btn-large btn-primary"
                                    >
                                        Create
                                    </button>

                                    <a
                                        href="{{ admin_url('testimonials') }}"
                                        class="btn btn-default btn-spacing"
                                    >
                                        Cancel <span>and Return</span>
                                    </a>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

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
