@php
$form_id = "property-pdf-form";
$form_id .= !empty($uniqueID) ? '-'.$uniqueID : false;
$property->ref = !empty($property->ref) ? $property->ref : 'PW';
@endphp

@push('styles')
<style>
#pdf-download-modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.5);
}

#pdf-download-modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border-radius: 10px;
    width: 80%;
    max-width: 500px;
    text-align: center;
    position: relative;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

#pdf-download-modal-close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    position: absolute;
    right: 10px;
    top: 10px;
}

#pdf-download-modal-close:hover {
    color: #000;
}
</style>
@endpush

@push('scripts')
<script>
(function() {
    document.addEventListener('DOMContentLoaded', function() {
        const modalKey = 'pdf_download_modal_shown';
        
        // Check if modal has been shown in this session
        if (!sessionStorage.getItem(modalKey)) {
            setTimeout(function() {
                const modal = document.createElement('div');
                modal.id = 'pdf-download-modal';
                modal.innerHTML = `
                    <div id="pdf-download-modal-content">
                        <span id="pdf-download-modal-close">&times;</span>
                        <h2>Download Property PDF</h2>
                        <p>Would you like to download the property details?</p>
                        <div class="form-group form-group-1 text-center">
                            <button onclick="document.getElementById('pdf-download-modal').style.display = 'none';" class="btn btn-primary">Download PDF</button>
                        </div>
                    </div>
                `;
                
                document.body.appendChild(modal);
                
                // Show modal
                modal.style.display = 'block';
                
                // Mark as shown in session
                sessionStorage.setItem(modalKey, 'true');
                
                // Close on (x) click
                document.getElementById('pdf-download-modal-close').onclick = function() {
                    modal.style.display = 'none';
                };
                
                // Close on outside click
                modal.onclick = function(event) {
                    if (event.target === modal) {
                        modal.style.display = 'none';
                    }
                };
            }, 5000); // 5 seconds delay
        }
    });
})();
</script>
@endpush

<form
    action="{{ url('ajax/property-pdf-download/' . $property->ref) }}"
    method="POST"
    data-toggle="validator"
    id="ajax-form-{{ $form_id }}"
>
    @csrf

    <div id="response-{{ $form_id }}"></div>

    <input type="hidden" name="recaptcha_token">
    <input type="hidden" name="property_ref" value="{{ $property->ref }}">

    <div class="-fields">

        {{-- Full name --}}
        <div class="form-group form-group-1 -transparent-bg u-mb1">
            <input
                type="text"
                name="fullname"
                class="form-control"
                placeholder="Your name *"
                value="{{ settings('members_area') == '1' && Auth::check() ? Auth::user()->fullname : '' }}"
                required
            >
            <span class="form-control-feedback" aria-hidden="true"></span>
        </div>

        {{-- Email --}}
        <div class="form-group form-group-1 -transparent-bg u-mb1">
            <input
                type="email"
                name="email"
                class="form-control"
                placeholder="Email *"
                value="{{ settings('members_area') == '1' && Auth::check() ? Auth::user()->email : '' }}"
                @if(settings('members_area') == '1' && Auth::check()) readonly @endif
                required
            >
            <span class="form-control-feedback" aria-hidden="true"></span>
        </div>

        {{-- Telephone --}}
        <div class="form-group form-group-1 -transparent-bg u-mb1">
            <input
                type="tel"
                name="telephone"
                class="form-control"
                placeholder="Phone number"
                value="{{ settings('members_area') == '1' && Auth::check() ? Auth::user()->telephone : '' }}"
            >
            <span class="form-control-feedback" aria-hidden="true"></span>
        </div>

        {{-- Terms --}}
        <div class="form-group">
            <div class="customcheck">
                <input
                    type="checkbox"
                    id="pdf-checkbox-1"
                    name="terms"
                    value="yes"
                    class="styled-checkbox"
                    required
                >
                <label for="pdf-checkbox-1">
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
                    id="pdf-checkbox-2"
                    name="newsletter"
                    value="yes"
                    class="styled-checkbox"
                    checked
                >
                <label for="pdf-checkbox-2">
                    Subscribe to the monthly newsletter
                </label>
            </div>
        </div>

        {{-- Submit --}}
        <div class="form-group form-group-1 text-center">
            <input type="hidden" name="ref" value="{{ $property->ref }}">
            <input type="hidden" name="position" value="pdf-download">

            <div
                id="gr-{{ $form_id }}"
                class="g-recaptcha-pw"
                data-sitekeypw="{{ settings('recaptcha_public_key') }}"
                data-sizepw="invisible"
                data-callbackpw="pdfDownloadSubmit"
                data-counterpw=""
            ></div>

            <button
                type="submit"
                id="btn-{{ $form_id }}"
                class="btn btn-main-downloadpdf"
            >
                Submit &amp; Download PDF
            </button>
        </div>

    </div>
</form>

<style>

.form-check {
    color: #989898;
}
.form-check a {
    color: #989898;
    text-decoration: none;
    transition: color 0.3s ease;
}
.form-check a:hover {
    color: #d9b483      ;
}
input[type="checkbox"] {
    accent-color: #000000;
}
.form-check-input:checked {
    background-color: #000000;
    border-color: #000000;
}
.form-control {
    border: 1px solid #ccc;
    font-size: 13px;
    font-weight: 400;
    color: #252525;
    height: auto;
    padding: 12px 15px;
    box-shadow: none;
    border-radius: 0;
    resize: none;
    width: 100%;
    margin-bottom: 15px;
}

.form-control::placeholder {
    color: #252525;
    text-transform: uppercase;
}

.country-select {
    border: 1px solid #ccc;
    font-size: 13px;
    font-weight: 400;
    color: #252525;
    height: auto;
    padding: 12px 15px;
    box-shadow: none;
    border-radius: 0;
}

.btn-main-downloadpdf {
    padding: 12px 15px;
    margin-top:25px;
    border-radius: 0;
    background: #ffffff;
    border: 1px solid #d9b483;
    color: #d9b483;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-main-downloadpdf:hover {
    background: #d9b483;
    color: #ffffff;
}
</style>


<script type="text/javascript">
function pdfDownloadSubmit(response) {
    $("#ajax-form-<?=$form_id?>").find('input[name="recaptcha_token"]').val(response);
    $("#ajax-form-<?=$form_id?>").trigger('submit');
}

$(document).ready(function() {
    // Add CSRF token to all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#ajax-form-<?=$form_id?>').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        var responseDiv = $('#response-<?=$form_id?>');
        
        // Show loading message
        responseDiv.html('<div class="alert alert-info">Processing your request...</div>');
        submitBtn.prop('disabled', true);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    responseDiv.html('<div class="alert alert-success">Thank you for your request</div>');
                    form[0].reset();
                    
                    if (response.pdf_url) {
                        // Open PDF in new tab
                        window.open(response.pdf_url, '_blank');
                    }
                } else {
                    responseDiv.html('<div class="alert alert-danger">' + (response.message || 'An error occurred') + '</div>');
                }
            },
            error: function(xhr, status, error) {
                var message = 'An error occurred. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    message = xhr.responseJSON.error;
                }
                responseDiv.html('<div class="alert alert-danger">' + message + '</div>');
                
                // Log error for debugging
                console.error('Form submission error:', error);
                console.error('Server response:', xhr.responseText);
            },
            complete: function() {
                submitBtn.prop('disabled', false);
            }
        });
    });
});
</script>

