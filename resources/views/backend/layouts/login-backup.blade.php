<!DOCTYPE html>
<html lang="en">
    @include('backend.layouts.parts.admin-header-login')
    <body class="login">
        <section id="login-row">
            <div class="container">
                <a href="#" target="_blank">
                  <img class="logo responsivejs-img" src="{{url('assets/admin/build/images/login/logo.png')}}" class="responsivejs-img" data-src="{{url('assets/admin/build/images/login/mobile/logo.png')}}" alt="logo" />
                </a>
                <div class="login-panel-container">
                    <div class="login-panel">
                        <a href="#"><img class="custom-logo" src="{{url('assets/admin/build/images/login/custom-logo.png')}}" alt="logo" /></a>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <input id="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus  autocomplete="off" placeholder="Email address">
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required autocomplete="off" placeholder="Password">
                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                            <input type="submit" name="submit" id="submit_btn" value="LOG IN" />
                            <p class="text-center"><a href="#"  data-toggle="modal" data-target="#forgotPasswordModal">contact us</a> if you have forgotten your password</p>

                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>

                        </form>
                    </div>
                </div>
            </div>
        </section>

        <section id="services-row" style="opacity: 0; position: relative; z-index: -9999;">
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
        </section>

        <section id="footer-row">
            <div class="container">
                <ul class="footer-social">
                    <li><a href="#" target="_blank" aria-label="Visit our LinkedIn profile"  class="social-linkedIn"></a></li>
                    <li><a href="#" target="_blank" aria-label="Visit our Twitter profile" class="social-twitter"></a></li>
                    <li><a href="#" target="_blank" aria-label="Visit our Facebook profile" class="social-facebook"></a></li>
                    <li><a href="#" target="_blank" aria-label="Visit our Google+ profile" class="social-gplus"></a></li>
                    <li><a href="#" target="_blank" aria-label="Visit our Pinterest profile" class="social-pinterest"></a></li>
                    <li><a href="https://www.instagram.com/terezaestates/" aria-label="Visit our Instagram profile" target="_blank" class="social-instagram"></a></li>
                </ul>
                <p>All rights reserved <?=date('Y');?>.</p>
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
        <script src="{{url('assets/admin/vendors/jquery/dist/jquery.min.js')}}"></script>
        <script src="{{url('assets/admin/vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    </body>
</html>
