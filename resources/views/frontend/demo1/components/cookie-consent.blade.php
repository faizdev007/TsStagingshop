@if(!Cookie::get('cookie_consent'))
<div id="cookie-overlay2" style="position: fixed; top:0; left:0; width:100%; height:100%; 
            background: rgba(0,0,0,0.6); z-index: 9999;">
</div>
<div id="cookie-consent" 
     style="position: fixed; bottom: 0; width: 100%; background: rgba(255, 255, 255, 0.7); color:#000; padding: 20px; z-index: 9999;">
    <div style="max-width: 900px; margin: auto;">
        <div class="w-full d-flex justify-content-end">
            <button id="close-main" class="btn btn-close mb-3"></button>
        </div>
        <p class="text-justify text-center text-md-start">
            To provide the best experience, this website uses essential and optional cookies to ensure the proper functioning, performance, and personalization of our services. Optional cookies help us analyze traffic patterns, optimize user experience, and tailor advertising based on your interests. By clicking “Accept All Cookies,” you authorize us to store and process data collected through these technologies. You may manage your preferences through “Manage Preferences.” For more information, please see our <a href="/cookie-policy" class="text-nowrap" style="color:#545250;">Cookie Policy.</a>
        </p>

        <div class="d-md-flex gap-2 justify-content-center">
            <button id="accept-all" class="flex-1 w-100 fw-semibold"
                style="background:#d9b483; color:#fff; padding:10px 20px; border:2px solid #d9b483; margin:5px; font:bold;" 
                onmouseover="this.style.background='#fff'; this.style.color='#000';" 
                onmouseout="this.style.background='#d9b483'; this.style.color='#fff';">
                Accept All Cookies
            </button>
    
            <button id="accept-essential"  class="flex-1 w-100 fw-semibold"
                style="background:#fff; color:#000; padding:10px 20px; border:2px solid #d9b483; margin:5px;" 
                onmouseover="this.style.background='#d9b483'; this.style.color='#fff'" 
                onmouseout="this.style.background='#fff'; this.style.color='#000'">
                Strictly Necessary Cookies
            </button>
    
            <button id="customize-btn" class="flex-1 w-100 fw-semibold "
                style="background:#fff; color:#000; padding:10px 20px; border:2px solid #d9b483; margin:5px;" 
                onmouseover="this.style.background='#d9b483'; this.style.color='#fff'" 
                onmouseout="this.style.background='#fff'; this.style.color='#000'">
                Manage Preferences
            </button>
        </div>
        
    </div>
</div>
@endif


<!-- Background Overlay -->
<div id="cookie-overlay" 
     style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; 
            background: rgba(0,0,0,0.6); z-index: 99998;">
</div>

<!-- Centered Cookie Settings Modal -->
<div id="cookie-modal" 
    class=""
     style="display:none; position: fixed; top: 50%; left: 50%; 
            transform: translate(-50%, -50%); background:#fff; color:#000; width: 98%; 
            max-width: 550px; padding: 15px; 
            box-shadow: 0px 0px 20px rgba(0,0,0,0.4); z-index: 99999;">
            
    <h3 style="margin-bottom: 15px; text-align:center;">Manage Consent</h3>
    
    <p>Manage how cookies are used on this site. Essential cookies run by default; optional cookies help improve your experience.</p>

    <div class="position-relative" style="margin-bottom:10px; padding:10px; border:1px solid #ddd;">
        <div class="d-flex justify-content-between py-2">
            <strong>Essential Cookies</strong>
            <div class="align-content-center">
                
            </div>
        </div>
        <p class="f-15 m-0">Used to deliver relevant ads and measure marketing performance.</p> 
        <div class="form-group w-100 mt-2">
            <div class="customcheck d-flex justify-content-end gap-2">
                <label>
                    Always Active
                </label>
                <input type="checkbox" class="styled-checkbox" checked disabled>
            </div>
        </div>   
    </div>

    <div class="" style="margin-bottom:10px; padding:10px; border:1px solid #ddd;">
        <div class="d-flex justify-content-between py-2">
            <strong>Analytics Cookies</strong>
            <div class="align-content-center">
                
            </div>
        </div>
        <p class="f-15 m-0">Used to measure site performance.</p> 
        <div class="form-group w-100 mt-2">
            <div class="customcheck d-flex gap-2 justify-content-end">
                <label>
                    Allow 
                </label>
                <input type="checkbox" class="styled-checkbox" id="analytics">
            </div>
        </div>   
    </div>
    
    <div class="" style="margin-bottom:10px; padding:10px; border:1px solid #ddd;">
        <div class="d-flex justify-content-between py-2">
            <strong>Marketing Cookies</strong>
            <div class="align-content-center">
                
            </div>
        </div>
        <p class="f-15 m-0">Used to deliver relevant ads and measure marketing performance.</p>    
        <div class="form-group w-100 mt-2">
            <div class="customcheck d-flex justify-content-end gap-2">
                <label>
                    Allow 
                </label>
                <input type="checkbox" class="styled-checkbox" id="marketing">
            </div>
        </div>
    </div>

    <div class="d-flex gap-3" style="text-align:center; margin-top:20px;">
        <button id="close-modal" class="fw-semibold flex-1 w-100" 
            style="background:#fff; color:#000; padding:10px 20px; border:2px solid #d9b483;" 
            onmouseover="this.style.background='#d9b483'; this.style.color='#fff'" 
            onmouseout="this.style.background='#fff'; this.style.color='#000'">
            Cancel
        </button>

        <button id="save-custom" class="fw-semibold flex-1 w-100"
            style="padding:10px 20px; background:#d9b483; color:white; border:2px solid #d9b483;" 
            onmouseover="this.style.background='#fff'; this.style.color='#000';" 
            onmouseout="this.style.background='#d9b483'; this.style.color='#fff';">
            Save Settings
        </button>
    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    // Main elements
    var banner = document.getElementById('cookie-consent');
    var modal = document.getElementById('cookie-modal');
    var overlay = document.getElementById('cookie-overlay');
    var mainoverlay = document.getElementById('cookie-overlay2');
    var closemain = document.getElementById('close-main');

    // Buttons
    var customBtn = document.getElementById('customize-btn');
    var closeBtn = document.getElementById('close-modal');
    var saveBtn = document.getElementById('save-custom');
    var essentialBtn = document.getElementById('accept-essential');
    var allBtn = document.getElementById('accept-all');

    // ---- SHOW MODAL ----
    if (customBtn) {
        customBtn.addEventListener('click', function () {
            modal.style.display = "block";
            overlay.style.display = "block";
        });
    }

    if(closemain){
        closemain.addEventListener('click',function(){
            banner.style.display = "none"; mainoverlay.style.display = "none"
        });
    }

    // ---- CLOSE MODAL ----
    if (closeBtn) {
        closeBtn.addEventListener('click', function () {
            modal.style.display = "none";
            overlay.style.display = "none";
        });
    }

    if (overlay) {
        overlay.addEventListener('click', function () {
            modal.style.display = "none";
            overlay.style.display = "none";
        });
    }

    // ---- SAVE CUSTOM SETTINGS ----
    if (saveBtn) {
        saveBtn.addEventListener('click', function () {

            let analytics = document.getElementById("analytics").checked;

            fetch("{{ route('cookie.custom') }}", {
                method: "POST",
                headers: { 
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ analytics, marketing })
            }).then(() => {
                modal.style.display = "none";
                overlay.style.display = "none";
                banner.style.display = "none";
                mainoverlay.style.display = "none";
            });

        });
    }

    // ---- ACCEPT ESSENTIAL ONLY ----
    if (essentialBtn) {
        essentialBtn.addEventListener('click', function () {
            fetch("{{ route('cookie.acceptEssential') }}")
                .then(() => {banner.style.display = "none"; mainoverlay.style.display = "none"});
        });
    }

    // ---- ACCEPT ALL ----
    if (allBtn) {
        allBtn.addEventListener('click', function () {
            fetch("{{ route('cookie.acceptAll') }}")
                .then(() => {banner.style.display = "none"; mainoverlay.style.display = "none"});
        });
    }

});
</script>
