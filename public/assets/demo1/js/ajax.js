$(function () {
  function ajaxForm(id) {
    
    var formObject = $("#ajax-form-" + id);
    
    formObject.submit(function (e) {
      e.preventDefault();
      var data = $(this).serialize() + '&url=' + window.location.href;
      var url = $(this).attr("action");
      var button_txt = $("#btn-" + id).html();
        // var widgetId = '';  
        $("#btn-" + id).html('loading...');
        formObject.find('input, select, textarea').removeClass('error-feild');
  
        $.ajax({
          type: "POST",
          dataType: "json",
          url: url,
          data: data,
          success: function (e) {
            // we apply red border to fields now
            if (e.invalidFeilds) {
              var _invalidFeilds = JSON.parse(e.invalidFeilds);
              for (var i = 0; i < _invalidFeilds.length; i++) {
                var _feild = _invalidFeilds[i];
                formObject.find('[name="' + _feild + '"]').addClass('error-feild');
              }
            }
            if (e.flag == 3) {
              $("#response-"+id).html(e.alert);
              return false;
            }if (e.flag === 0) {
                $("#response-"+id).html(e.alert);
                return;
            } else {
              if (e.flag) {
                $("#response-" + id).html(e.alert);
                $("#ajax-form-" + id).find("input[type=email],input[type=tel],input[type=text],textarea,select").val("");
              }
            }
  
            setTimeout(function(){
              $("#response-" + id).html('');
            },5000);
            
            if (id == "property-alert-form") {
              if (e.flag == 1) {
                // Give it 2 seconds then hide the modal...
                setTimeout(function () {
                  // Hide Modal Manually as having issues loading BS4 JS....?
                  $('.property-alert-modal').fadeOut();
                  $('.modal-backdrop').hide();
                  $('body').removeClass('modal-open');
                }, 2000)
              }
            }
            //console.log(e);
            $("#btn-" + id).html(button_txt);
          },
          complete: function (e) {
            //console.log(e);
            $("#btn-" + id).html(button_txt);
            return false;
          },
          fail: function (e) {
            //console.log(e);
          }
        });
        tokenGenerated = false;
        return false;
    });
  }

  /** AJAX FORM **/
  ajaxForm("newsletter-form");
  ajaxForm("contact-form");
  ajaxForm("property-sidebar-form");
  ajaxForm("valuation-form");
  ajaxForm("generic-bottom-form");
  ajaxForm("generic-rhs-form");
  ajaxForm("property-bottom-form");
  ajaxForm("property-alert-form");

  ajaxForm("home-cta-1-form");
  ajaxForm("home-cta-2-form");
  ajaxForm("home-cta-3-form");


  $('.shortlist-add').on('click', function (e) {
      e.preventDefault();
      const block = $(this);
      console.log(block);
      const url = $(this).attr('data-url');
      const pid = $(this).attr('data-property-id');
      $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type: "POST",
          dataType: "json",
          url: url,
          data: {property_id:pid},
          success: function (response) {
            if(response.flag == 1){
              block.html(`<i class="fas fa-heart"></i>`);
            }else{
              block.html(`<i class="far fa-heart"></i>`);
            }
          },
          error: function (xhr) {
              console.error(xhr.responseText);
          }
      });
  });

});

// function generatecaptchatoken(id) {
//   var gObject = $("#ajax-form-" + id).find(".g-recaptcha-pw");
//   var grId = gObject.attr('id');
//   var ajaxsitekey = gObject.data('sitekeypw');
//   var ajaxcallback = gObject.data('callbackpw');
//   var ajaxcounter = gObject.attr('data-counterpw');

//   if (ajaxcounter == '') {
//     widgetId = grecaptcha.render(grId, {
//       'sitekey': ajaxsitekey,
//       'callback': ajaxcallback,
//       'size': "invisible"
//     });

//     gObject.attr('data-counterpw', widgetId);
//     // grecaptcha.reset(widgetId);
//     grecaptcha.execute(widgetId);
//   } else {
//     // grecaptcha.reset(ajaxcounter);
//     grecaptcha.execute(ajaxcounter);
//   }
// }