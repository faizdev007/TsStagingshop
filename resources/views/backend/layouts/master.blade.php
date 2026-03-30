<!DOCTYPE html>
<html lang="en">
    @include('backend.layouts.parts.admin-header')
    <body class="nav-md {{ $bodyClass ?? '' }}">
        <input type="hidden" class="base-url" name="base_url" value="{{ admin_url('') }}">
        <input type="hidden" class="site-url" name="site_url" value="{{ url('') }}">
        <input type="hidden" class="settings-currency" name="settings_currency" value="{{ settings('currency_symbol') }}">

        <div class="container body">
            <div class="main_container">
                <div class="col-md-3 left_col">
                    <div class="left_col scroll-view">
                        @include('backend.layouts.parts.left')
                     </div>
                </div>

                <!-- top navigation -->
                <div class="top_nav">
                    <div class="nav_menu">
                        @include('backend.layouts.parts.top')
                    </div>
                </div>
                <!-- /top navigation -->

                <!-- page content -->
                <div class="right_col" role="main">
                    @include('backend.layouts.parts.counter')
                    <div class="notification-container">
                        @include('backend.layouts.parts.notify')
                    </div>
                    <div class="admin-container">
                        @include('backend.layouts.parts.page-title')
                        @yield('admin-content')
                    </div>

                </div>
                <!-- /page content -->

                <!-- footer content -->
                <footer>
                    <div class="pull-right">
                        Estate Agency Management Software 
                    </div>
                    <div class="clearfix"></div>
                </footer>
                <!-- /footer content -->

                <!-- Modal -->
                <div class="modal fade" id="global-modal">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel"><!-- jQuery Populated --></h4>
                            </div><!-- /.modal-header -->
                            <div class="modal-body">
                                <!-- /jQuery Populated -->
                            </div><!-- /.modal-body -->
                            <div class="modal-footer">
                                <a class="btn btn-small btn-primary" data-dismiss="modal" href="#">Close</a>
                            </div><!-- /.modal-footer -->
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->

                @include('backend.layouts.parts.admin-footer')
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded',function(){
                setTimeout(()=>{
                    document.getElementById('success-message')?.remove();
                },5000);
            });
        </script>
    </body>
</html>
