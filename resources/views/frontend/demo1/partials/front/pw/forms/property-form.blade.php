<form
    action="{{ url('/property-enquire/') }}"
    method="POST"
    class="required-form property-form--trigger"
    id="{{ $id }}"
>
    @csrf

    <div class="succemsg"></div>

    <div class="form-group">
        <input
            type="text"
            name="name"
            id="name"
            class="form-control required"
            placeholder="NAME*"
        >

        <input type="hidden" name="property_id" value="{{ $data['property']->id }}">
        <input type="hidden" name="reference_code" value="{{ $data['property']->reference_code }}">
    </div>

    <div class="form-group">
        <input
            type="text"
            name="email"
            id="email"
            class="form-control required"
            placeholder="EMAIL*"
        >
    </div>

    <div class="form-group -intel">
        <input
            type="text"
            name="phone"
            id="phone"
            class="form-control"
            placeholder="PHONE"
            required
            autofocus
            minlength="8"
            maxlength="15"
        >
    </div>

    {{-- Subject fixed as N/A --}}
    <input type="hidden" name="subject" value="N/A">

    <div class="form-group">
        <textarea
            id="message"
            name="message"
            class="form-control required"
            rows="3"
            placeholder="DESCRIPTION*"
        ></textarea>
    </div>

    <div class="form-group">
        <div class="customcheck">
            <input
                class="styled-checkbox"
                id="styled-checkbox-1"
                name="terms"
                type="checkbox"
                value="yes"
                checked
                required
            >
            <label for="styled-checkbox-1">
                I agree to the
                <a href="{{ url('terms-conditions') }}">Terms of Use</a>
                &amp;
                <a href="{{ url('privacy') }}">Privacy Policy</a>.
            </label>
        </div>
    </div>

    <div class="form-group">
        <div class="customcheck">
            <input
                class="styled-checkbox"
                id="styled-checkbox-2"
                type="checkbox"
                name="newsletter"
                value="yes"
            >
            <label for="styled-checkbox-2">
                Subscribe to the monthly newsletter
            </label>
        </div>
    </div>

    <button
        id="enquire_now_new"
        type="submit"
        name="button"
        class="g-recaptcha btn-main enquire_now_new"
        data-sitekey="{{ env('reCAPTCHA_site_key') }}"
        data-callback="sendEnquery"
    >
        SEND MESSAGE
    </button>
</form>

@push('frontend-footer-style')
    <link rel="stylesheet" href="{{ asset('assets/css/intlTelInput.min.css') }}">
@endpush
