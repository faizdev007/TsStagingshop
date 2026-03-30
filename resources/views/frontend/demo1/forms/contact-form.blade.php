@php $form_id = "contact-form"; @endphp
<form
    action="{{ url('ajax/contact') }}"
    method="POST"
    data-toggle="validator"
    id="ajax-form-{{ $form_id }}"
>
    @csrf

    <div id="response-{{ $form_id }}" class="text-center"></div>

    <input type="hidden" name="recaptcha_token" id="recaptcha_token">

    <div class="row row-spacing-12">

        {{-- Full name --}}
        <div class="col-md-6 col-sm-6 col-6">
            <div class="form-group form-group-1 u-mb1">
                <input
                    type="text"
                    name="fullname"
                    class="form-control"
                    placeholder="Full Name *"
                    value="{{ settings('members_area') == '1' && Auth::check() ? Auth::user()->fullname : '' }}"
                    required
                    autocomplete="name"
                >
                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
            </div>
        </div>

        {{-- Telephone --}}
        <div class="col-md-6 col-sm-6 col-6">
            <div class="form-group form-group-1 u-mb1">
                <input
                    type="tel"
                    name="telephone"
                    id="phone"
                    class="form-control"
                    placeholder="PHONE"
                    onblur="validatePhone(this.value)"
                    autocomplete="number"
                    value="{{ settings('members_area') == '1' && Auth::check() ? Auth::user()->telephone : '' }}"
                >
                <!-- pattern="^\+[0-9]+" -->
                <!-- @if(settings('members_area') == '1' && Auth::check()) readonly @endif -->
                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>

                <div
                    id="phone-warning-{{ $form_id }}"
                    class="text-danger"
                    style="display:none; color:red; margin-top:5px;"
                >
                    Invalid phone number format. Please Select the country code
                </div>
            </div>
        </div>

        {{-- Email --}}
        <div class="col-md-6 col-sm-6 col-6">
            <div class="form-group form-group-1 u-mb1">
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="EMAIL *"
                    value="{{ settings('members_area') == '1' && Auth::check() ? Auth::user()->email : '' }}"
                    @if(settings('members_area') == '1' && Auth::check()) readonly @endif
                    required
                >
                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
            </div>
        </div>

        {{-- Reason --}}
        <div class="col-md-6 col-sm-6 col-6">
            <div class="form-group form-group-1 -transparent-bg u-mb1">
                <select class="form-control" name="reason">
                    <option style="background:#d9b483;color:#ffffff;border:1px solid #000000;">
                        WHAT IS YOUR QUERY ABOUT
                    </option>
                    <option class="sale" style="background:#c9a26eff;color:#ffffff;border:1px solid #000000;">Sale</option>
                    <option class="rent" style="background:#d9b483;color:#ffffff;border:1px solid #000000;">Rent</option>
                    <option class="New Developments" style="background:#c9a26eff;color:#ffffff;border:1px solid #000000;">New Developments</option>
                    <option class="International" style="background:#d9b483;color:#ffffff;border:1px solid #000000;">International</option>
                    <option class="Careers" style="background:#c9a26eff;color:#ffffff;border:1px solid #000000;">Careers</option>
                    <option class="Media" style="background:#d9b483;color:#ffffff;border:1px solid #000000;">Media</option>
                    <option class="Other" style="background:#c9a26eff;color:#ffffff;border:1px solid #000000;">Other</option>
                </select>
                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
            </div>
        </div>

        {{-- Message --}}
        <div class="col-md-12 col-sm-12 col-12">
            <div class="form-group form-group-1">
                <textarea
                    name="message"
                    class="form-control"
                    placeholder="MESSAGE"
                    rows="2"
                ></textarea>
                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
            </div>
        </div>

        {{-- Submit --}}
        <div class="col-md-12 col-sm-12 col-12">
            <div class="form-group-1 text-center u-mt1">
                <button
                    type="submit"
                    id="btn-{{ $form_id }}"
                    class="button -secondary f-700 f-14 -x-large text-uppercase"
                >
                    submit
                </button>
            </div>
        </div>

    </div>
</form>


<script type="text/javascript">
    function validatePhone(phone) {
        const formId = '<?=$form_id?>';
        const warningElement = document.getElementById('phone-warning-' + formId);
        const submitButton = document.querySelector('#btn-' + formId);
        
        if (!phone.startsWith('+')) {
            // Show warning
            warningElement.style.display = 'block';
            // Disable submit button
            submitButton.disabled = true;
            submitButton.classList.add('disabled');
            return false;
        } else {
            // Hide warning
            warningElement.style.display = 'none';
            // Enable submit button
            submitButton.disabled = false;
            submitButton.classList.remove('disabled');
            return true;
        }
    }

    // Add form submission validation
    document.getElementById('ajax-form-<?=$form_id?>').addEventListener('submit', function(event) {
        const phoneInput = this.querySelector('input[name="telephone"]');
        if (phoneInput && phoneInput.value && !phoneInput.value.startsWith('+')) {
            event.preventDefault();
            document.getElementById('phone-warning-<?=$form_id?>').style.display = 'block';
            return false;
        }
    });
</script>

<style>
.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>