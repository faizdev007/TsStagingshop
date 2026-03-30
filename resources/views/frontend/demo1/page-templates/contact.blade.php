@push('body_class')contact-page @endpush

@extends('frontend.demo1.layouts.frontend')

@section('main_content')
<!-- inner-hero -->
<section class="inner-hero">
    <div class="container">
          @include('frontend.demo1.partials.front.pw.breadcrumb')
        <h1 class="f-four f-45">Contact Us</h1>
    </div>
</section>
<!-- End inner-hero -->

<!-- Contact Wrp -->
<section class="contact-wrp u-circle-style-1">
    <div class="container">

        <div class="get-touch-wrp">
            <h2 class="f-two f-32">Get in Touch With Us</h2>
            <div class=" d-none d-sm-block">
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <div class="cntinfo-box">
                            <div class="img-div">
                                <a href="tel:{{ settings('telephone') }}"><img src="{{themeAsset('images/conrad-images/call.png') }}" class="u-pointer hvr-float" alt="Phone"></a>
                            </div>
                            <p>PHONE</p>
                            <a href="tel:{{ settings('telephone') }}">{{ settings('telephone') }}</a>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="cntinfo-box center-box">
                            <div class="img-div">
                                <a href="https://goo.gl/maps/ZiooiQ5B1tSnwfDJ9" target="_blank"><img src="{{themeAsset('images/conrad-images/location.png') }}" class="u-pointer hvr-float" alt="Our Office"></a>
                            </div>
                            <p>OUR OFFICE</p>
                            <a  target="_blank"> {{ settings('contact_address') }}
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="cntinfo-box">
                            <div class="img-div">
                                <a href="mailto:{{ settings('email_contact_1') }}"><img src="{{themeAsset('images/conrad-images/mail.png') }}" class="u-pointer hvr-float" alt="Email"></a>
                            </div>
                            <p>EMAIL</p>
                            <a href="mailto:{{ settings('email_contact_1') }}">{{ settings('email_contact_1') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="cnt-form">


            <p>Luxury Property Dubai: Whether you are looking to buy, sell or develop, we can help!<br> Please fill out the contact form and our advisors will reply to you within 24-hours.</p>
            @include('frontend.demo1.forms.contact-form')
        </div>

    </div>
</section>
<section class="map-section map-wrp">
    
        <div class="map-container container-fluid">
           <!-- <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d462561.6572648617!2d55.28480!3d25.19097!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e5f43496ad9c645%3A0xbde66e5084295162!2sDubai%20-%20United%20Arab%20Emirates!5e0!3m2!1sen!2sro!4v1672300018219!5m2!1sen!2sro" width="100%" height="415" style="border:0;" allowfullscreen=""  referrerpolicy="no-referrer-when-downgrade"></iframe> -->
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1042.8088917419407!2d55.284730335573364!3d25.191185133347393!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x482a26b7999d8ef5%3A0x5f75a7b6622f5c03!2sTereza%20Estates!5e0!3m2!1sen!2sae!4v1708145490317!5m2!1sen!2sae" width="100%" height="415" style="border:0;" allowfullscreen=""  referrerpolicy="no-referrer-when-downgrade"></iframe>
            <!--div id="content"><p>{!! settings('contact_address') !!}</p></div-->
        </div>
    
</section>
<!-- ENd Contact Wrp -->
@endsection

