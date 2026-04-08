import $ from 'jquery';
window.$ = window.jQuery = $;

$(function(){
  function ajaxForm(id) {
  var formObject = $("#ajax-form-" + id);

  formObject.submit(function (e) {
    e.preventDefault();

    const SITE_KEY = document.querySelector('meta[name="recaptcha-key"]').content;

    var url = $(this).attr("action");
    var button_txt = $("#btn-" + id).html();

    $("#btn-" + id).html('loading...');
    $("#response-" + id).html('');
    formObject.find('input, select, textarea').removeClass('error-feild');

    // ✅ STEP 1: Generate token FIRST
    if (typeof grecaptcha === 'undefined') {
      console.error('reCAPTCHA not loaded');
      return false;
    }

    grecaptcha.ready(function () {
      grecaptcha.execute(SITE_KEY, { action: 'submit' }).then(function (token) {

        // ✅ STEP 2: Attach token to form
        if (!formObject.find('[name="recaptcha_token"]').length) {
          formObject.append('<input type="hidden" name="recaptcha_token">');
        }

        formObject.find('[name="recaptcha_token"]').val(token);

        // ✅ STEP 3: Prepare data AFTER token
        var data = formObject.serialize() + '&url=' + window.location.href;

        // ✅ STEP 4: AJAX call
        $.ajax({
          type: "POST",
          dataType: "json",
          url: url,
          data: data,

          success: function (e) {

            if (e.invalidFeilds) {
              var _invalidFeilds = JSON.parse(e.invalidFeilds);
              for (var i = 0; i < _invalidFeilds.length; i++) {
                var _feild = _invalidFeilds[i];
                formObject.find('[name="' + _feild + '"]').addClass('error-feild');
              }
            }

            if (e.flag == 3) {
              $("#response-" + id).html(e.alert);
              return false;
            } else {
              if (e.flag) {
                $("#response-" + id).html(e.alert);
                formObject.find("input[type=email],input[type=tel],input[type=text],textarea,select").val("");
              }
            }

            if (id == "property-alert-form" && e.flag == 1) {
              setTimeout(function () {
                $('.property-alert-modal').fadeOut();
                $('.modal-backdrop').hide();
                $('body').removeClass('modal-open');
              }, 2000);
            }

            setTimeout(() => {
              fetch('/runJobs').catch(() => {});
            }, 10000);

            $("#btn-" + id).html(button_txt);
          },

          complete: function () {
            $("#btn-" + id).html(button_txt);
          },

          error: function (err) {
            console.error(err);
            $("#btn-" + id).html(button_txt);
          }
        });

      });
    });

    return false;
  });
}


  /** AJAX FORM **/
  ajaxForm("contact-form");
  ajaxForm("valuation-form");
  ajaxForm("generic-bottom-form");
  ajaxForm("generic-rhs-form");
  ajaxForm("property-sidebar-form");
  ajaxForm("property-bottom-form");
  ajaxForm("newsletter-form");
  ajaxForm("property-alert-form");

  ajaxForm("home-cta-1-form");
  ajaxForm("home-cta-2-form");
  ajaxForm("home-cta-3-form");

});