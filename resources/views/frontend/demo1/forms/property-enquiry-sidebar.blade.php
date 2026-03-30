@php
$form_id = "property-sidebar-form";
$form_id .= !empty($bottom_form) ? '-2' : false;
$form_id .= !empty($uniqueID) ? '-'.$uniqueID : false;
$property->ref = !empty($property->ref) ? $property->ref : 'PW';
$blockedEmails = [
    'ericjonesmyemail@gmail.com',
    'info@professionalseocleanup.com',
    'mike@monkeydigital.co',
    'yawiviseya67@gmail.com',
    'cheeck-tttt@gmail.com',
    'ebojajuje04@gmail.com'
];
@endphp

<form
    action="{{ route('sideformQuery', $property->ref) }}"
    method="POST"
    data-toggle="validator"
    id="ajax-form-{{ $form_id }}"
>
    @csrf

    <input type="hidden" name="recaptcha_token" id="recaptcha_token">

    <div class="-fields">

        <div
            id="email-block-warning"
            class="text-danger"
            style="display:none; color:red; margin-bottom:10px;"
        >
            Email Address blocked by admin
        </div>

        {{-- First name --}}
        <div class="form-group form-group-1 -transparent-bg u-mb1">
            <input
                type="text"
                name="firstname"
                class="form-control"
                placeholder="FIRST NAME *"
                value="{{ settings('members_area') == '1' && Auth::check() ? Auth::user()->firstname : '' }}"
                required
                autocomplete="off"
            >
            <span class="form-control-feedback" aria-hidden="true"></span>
        </div>

        {{-- Last name --}}
        <div class="form-group form-group-1 -transparent-bg u-mb1">
            <input
                type="text"
                name="lastname"
                class="form-control"
                placeholder="LAST NAME *"
                value="{{ settings('members_area') == '1' && Auth::check() ? Auth::user()->lastname : '' }}"
                required
                autocomplete="off"
            >
            <span class="form-control-feedback" aria-hidden="true"></span>
        </div>

        {{-- Email --}}
        <div class="form-group form-group-1 -transparent-bg u-mb1">
            <input
                type="email"
                name="email"
                class="form-control"
                placeholder="EMAIL ADDRESS *"
                value="{{ settings('members_area') == '1' && Auth::check() ? Auth::user()->email : '' }}"
                @if(settings('members_area') == '1' && Auth::check()) readonly @endif
                required
                autocomplete="off"
            >
            <span class="form-control-feedback" aria-hidden="true"></span>
        </div>

        {{-- Telephone --}}
        <div class="form-group form-group-1 -transparent-bg u-mb1">
            <input
                type="text"
                name="telephone"
                id="telephone-input"
                class="form-control"
                oninput="validatePhone(this)"
                value="{{ settings('members_area') == '1' && Auth::check() ? Auth::user()->telephone : '' }}"
                required
                autocomplete="off"
            >
            <!-- (settings('members_area') == '1' && Auth::check()) readonly  -->
            <span class="form-control-feedback" aria-hidden="true"></span>

            <div
                id="phone-warning-{{ $form_id }}"
                class="text-danger"
                style="display:none; color:red; margin-top:5px;"
            >
                Invalid phone number format. Please Select the country code
            </div>
        </div>

        {{-- Message --}}
        <div class="form-group form-group-1 -transparent-bg u-mb2">
            <input
                type="text"
                name="message"
                class="form-control"
                placeholder="MESSAGE"
                autocomplete="off"
            >
            <span class="form-control-feedback" aria-hidden="true"></span>
        </div>

        {{-- Terms --}}
        <div class="form-group">
            <div class="customcheck">
                <input
                    type="checkbox"
                    name="terms"
                    value="yes"
                    class="styled-checkbox"
                    id="styled-checkbox-1"
                    checked
                    required
                    autocomplete="off"
                >
                <label for="styled-checkbox-1">
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
                    type="checkbox"
                    name="newsletter"
                    value="yes"
                    class="styled-checkbox"
                    id="styled-checkbox-2"
                    checked
                    autocomplete="off"
                >
                <label for="styled-checkbox-2">
                    Subscribe to the monthly newsletter
                </label>
            </div>
        </div>

        {{-- Submit --}}
        <div class="form-group form-group-1 text-center">
            <input type="hidden" name="ref" value="{{ $property->ref }}">
            <input type="hidden" name="position" value="sidebar">

            <div
                id="gr-{{ $form_id }}"
                class="g-recaptcha-pw"
                data-sitekeypw="{{ settings('recaptcha_public_key') }}"
                data-sizepw="invisible"
                @if($form_id === 'property-sidebar-form-mobile')
                    data-callbackpw="pEnquirySideBarSubmitMobile"
                @else
                    data-callbackpw="pEnquirySideBarSubmit"
                @endif
                data-counterpw=""
            ></div>

            <div id="response-{{ $form_id }}"></div>

            <button
                type="submit"
                id="btn-{{ $form_id }}"
                class="btn btn-main-sumbit custom-btn-submit"
            >
                Email Enquiry
            </button>
        </div>

    </div>
</form>

<script type="text/javascript">
    // Blocked emails array
    const blockedEmails = @json($blockedEmails);

    function checkBlockedEmail(email) {
        const warningElement = document.getElementById('email-block-warning');
        const submitButton = document.querySelector('.custom-btn-submit');
        
        // Check if email domain is blocked
        const emailDomain = email.split('@')[1];
        const isBlocked = blockedEmails.some(blockedEmail => 
            email === blockedEmail || emailDomain === blockedEmail
        );

        if (isBlocked) {
            // Show warning
            warningElement.style.display = 'block';
            // Disable submit button
            submitButton.disabled = true;
            submitButton.classList.add('disabled');
        } else {
            // Hide warning
            warningElement.style.display = 'none';
            // Enable submit button
            submitButton.disabled = false;
            submitButton.classList.remove('disabled');
        }
    }

    function validatePhone(phone) {
        let value = phone.value;

        // Allow only one + and only at start
        value = value.replace(/(?!^)\+/g, ""); // remove + if not at start

        // Remove everything except digits, spaces, and +
        value = value.replace(/[^0-9 +]/g, "");

        // If + appears later (not first position), fix it
        if (value.indexOf('+') > 0) {
            value = value.replace(/\+/g, ''); // remove misplaced +
            value = '+' + value;              // put it back at start
        }

        phone.value = value;
        
        const formId = '<?=$form_id?>';
        const warningElement = document.getElementById('phone-warning-' + formId);
        const submitButton = document.querySelector('#btn-' + formId);
        
        if (!value.startsWith('+')) {
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

.custom-btn-submit.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-main-sumbit{
                padding: 12px 15px;
                display: block;
                width: 100%;
                border-radius: 0;
                background: #ffffff;
                border-color: #d9b483;
                color: #d9b483;
                font-size: 17px;
                font-weight: 600;
                /* margin: 35px 0; */
     
            }
        
    
    .custom-btn-container {
      display: flex;
      justify-content: space-between;
      width: 100%;
    }

    .custom-btn {
      width: 48%; /* Adjust this value for a slight gap between buttons */
      background-color: #d9b483;
      color: white;
      font-weight: 600;
      font-size: 15px;
      font-family: 'Work Sans', sans-serif;
      border-radius: 0;
      padding: 12px 15px;
      margin-top:-33px;
      margin-bottom:16px;
      transition: border-color 0.3s;
      border-color: #d9b483;
    }

    .call-btn {
      background-color: #d9b483; 
    }

    .whatsapp-btn {
     
      width: 48%; /* Adjust this value for a slight gap between buttons */
      background-color: #68c665;
      color: white;
      font-weight: 600;
      font-size: 15px;
      font-family: 'Work Sans', sans-serif;
      border-radius: 0;
      padding: 12px 15px;
      margin-top:-33px;
      margin-bottom:16px;
      transition: border-color 0.3s;
      border-color: #68c665;
    }

    .whatsapp-btn:hover {
      background-color: white;
      color: #68c665;
      border-color: #68c665;
    }

    .custom-btn:hover {
      border-color: #d9b483;
      background-color: white;
      color: #d9b483;
    }
      .custom-btn-submit:hover {
        border-color: #d9b483;
        background-color: #d9b483;
        color: white;
      
    }
  </style>
  

  <div class="mt-5">
    <div class="row">
      <div class="col">
        <div class="custom-btn-container gap-2">
            <a href="tel:+971585365111" class="btn w-100 custom-btn"><i class="fas fa-phone">&nbsp;</i>Call</a>
          
            <form method="POST" class="w-100 flex-1" action="{{ route('insertClick') }}">
                @csrf
                @method('POST')
                <input type="hidden" name="h1value" value="{{ $property->ref }}">
                <input type="hidden" name="h2value" value="{{ $property->name }}">
                <button class="btn  whatsapp-btn w-100" type="submit"  onclick="sendwhatsapp();"><i class="fab fa-whatsapp">&nbsp;</i>WhatsApp</button>
            </form>
        </div>
      </div>
    </div>
  </div>
  <script>


function sendwhatsapp() {
  var phonenumber = "+971585365111";

    // Trigger a click event on the "Click me" button

  // Fetch property information
  var h1Value = document.querySelector('.facility-bx--wrap h4 span')?.textContent || "N/A";
  var priceValue = (document.querySelector('.-js-price-display')?.textContent || "").trim() || "N/A";
  var h2Value = document.querySelector('.area_hide_for_whatsapp_message h5 span')?.textContent || "N/A";

  // Fetch current URL
  var currentURL = window.location.href;
  var currentCurrencytype = `{{ !empty(all_currencies()[get_current_currency()])? get_current_currency() :'AED' }}`;

  // Construct and open WhatsApp message
  var message = encodeURIComponent(
    `Hello Tereza Estates! I would like to get more information about this property I have seen on Your website:\n\n` +
    `*Reference ID:* ${h1Value}\n` +
    `*Price:* ${priceValue}\n` +
    `*Location:* ${h2Value}\n\n` +
    `*Link:* ${currentURL}/${currentCurrencytype}\n\n` +
    `Any changes made to this WhatsApp inquiry will result in the inquiry not being sent.`
  );

  var url = `https://wa.me/${phonenumber}?text=${message}`;

  window.open(url, '_blank').focus();
}

  </script>
              




