import $ from 'jquery';
window.$ = window.jQuery = $;

import intlTelInput from 'intl-tel-input';

document.addEventListener('DOMContentLoaded', function () {
    // jQuery
    let intlTelInputBoxes = document.querySelectorAll('[name="telephone"]')
    intlTelInputBoxes.forEach((intlTelInputBox) => {
        intlTelInput(intlTelInputBox, {
            allowExtensions: true,
            autoFormat: false,
            autoHideDialCode: false,
            autoPlaceholder: false,
            defaultCountry: "ae",
            ipinfoToken: "yolo",
            nationalMode: false,
            numberType: "MOBILE",
            preventInvalidNumbers: true,
            initialCountry: "auto",
            preferredCountries: ["ae","gb","us"],
            geoIpLookup: function(success) {
                 success("ae");
                },
            //utilsScript: "/assets/js/intlTelInput-jquery.min.js",
        });        
    }); 
})

