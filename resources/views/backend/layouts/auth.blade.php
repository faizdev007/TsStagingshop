<!DOCTYPE html>
<html lang="en">
    @include('backend.layouts.parts.admin-header-login')
    <body class="login">
        <section id="login-row">
            <div class="container">

                <div class="login-panel-container">
                    <div class="login-panel">
                        <a href="{{url('')}}"><img class="custom-logo" src="{{themeAsset('images/logos/logo.png')}}" alt="logo" /></a>
                        @include('backend.layouts.parts.notify')
                        @yield('content')
                    </div>
                </div>
            </div>
        </section>

        <!-- <section id="services-row" style="opacity: 0; position: relative; z-index: -9999;">
            <div class="container">
                <h2><strong>remember...</strong> we can also help you with</h2>
                <ul class="services">
                  <li>
                    <img src="{{url('assets/admin/build/images/login/service-graph.png')}}" alt="SEO" />
                    <span>SEO</span>
                  </li>
                  <li>
                    <img src="{{url('assets/admin/build/images/login/service-newsletter.png')}}" alt="Newsletter Marketing" />
                    <span>Newsletter Marketing</span>
                  </li>
                  <li>
                    <img src="{{url('assets/admin/build/images/login/service-design.png')}}" alt="Graphic Design" />
                    <span>Graphic Design</span>
                  </li>
                  <li>
                    <img src="{{url('assets/admin/build/images/login/service-software_integration.png')}}" alt="Software Integration" />
                    <span>Software Integration</span>
                  </li>
                  <li>
                    <img src="{{url('assets/admin/build/images/login/service-property_exposure.png')}}" alt="Property Exposure" />
                    <span>Property Exposure</span>
                  </li>
                </ul>
                <a href="#" class="more-btn" data-toggle="modal" data-target="#contactModal">Want to know more?</a>
           </div>
        </section> -->

        <head>


        <section id="footer-row">
            <div class="container">
                <ul class="footer-social">
                <li><a href="https://www.instagram.com/terezaestates/" target="_blank" class="footer-social"> <img src="{{asset('assets/admin/build/images/login/social-instagramss.png')}}" style="width:36px;height:36px;"></a></li>
                <li><a href="https://www.linkedin.com/company/87407725" target="_blank" class="footer-social"> <img src="{{asset('assets/admin/build/images/login/social-linkedinss.png')}}" style="width:36px;height:36px;"></a></li>
                    <li><a href="/" target="_blank" class="footer-social"> <img src="{{asset('assets/admin/build/images/login/social-twitters.png')}}" style="width:36px;height:36px;"></a></li>
                    <li><a href="https://www.facebook.com/profile.php?id=100088111359986" target="_blank" class="footer-social"> <img src="{{asset('assets/admin/build/images/login/social-facebooks.png')}}" style="width:36px;height:36px;"></a></li>
                    <!-- <li><a href="https://www.instagram.com/terezaestates/" target="_blank" class="social-youtube"></a></li> -->
                    <!-- <li><a href="https://www.instagram.com/terezaestates/" target="_blank" class="social-tiktok"></a></li> -->
                    
                  
          
                   
                </ul>
               

                <!-- For Automatic Date
                <p> Copyright © 2023 terezaestates.com <?=date('Y');?>.</p> -->

                <p> Copyright © 2022-2025 terezaestates.com</p>
            
            </div>
        </section>



        <!-- Modal -->
        <div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">We can do more for you!</h4>
                </div>
                <div class="modal-body">
                  <form class="ajax-form" data-action="">
                    <p>If you would like more information about how we could help improve your digital marketing; please contact us and we will explain the processes we are able to conduct</p>
                    <div class="notif"></div>
                    <div class="row">
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" name="company_name" value="" placeholder="Company Name *">
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" name="your_name" value="" placeholder="Your Name *">
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" name="contact_number" value="" placeholder="Contact Number *">
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" name="email_address" value="" placeholder="Email Address *">
                      </div>
                    </div>
                    <textarea name="comments" placeholder="Whats up?"></textarea>
                    <div class="row">
                      <div class="col-md-12 col-sm-12 col-xs-12">
                        <input type="submit" value="SEND" placeholder="SEND">
                        <input type="hidden" name="action" value="contact" />
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="forgotPasswordModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Can't remember your password?</h4>
                    </div>
                    <div class="modal-body">
                        <form class="ajax-form" data-action="">
                            <p>Forgotten your password? No problem; contact us by completing the form below and we will be happy to help.</p>
                            <div class="notif"></div>
                            <div class="row">
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" name="company_name" value="" placeholder="Company Name *">
                              </div>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" name="your_name" value="" placeholder="Your Name *">
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" name="contact_number" value="" placeholder="Contact Number *">
                              </div>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" name="email_address" value="" placeholder="Email Address *">
                              </div>
                            </div>
                            <textarea name="comments" placeholder="Message"></textarea>
                            <div class="row">
                              <div class="col-md-12 col-sm-12 col-xs-12">
                                <input type="submit" value="SEND" placeholder="SEND">
                                <input type="hidden" name="action" value="reset" />
                              </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{asset('assets/admin/vendors/jquery/dist/jquery.min.js')}}"></script>
        <script src="{{asset('assets/admin/vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    </body>
</html>
