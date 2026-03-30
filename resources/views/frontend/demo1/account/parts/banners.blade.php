<section class="page-title -account-area -{{ request()->segment(1) }}">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="text-center">
                    @if(Auth::user())
                        <h1 class="f-two f-38 f-regular c-white u-mb0 u-mt0">Welcome, {{ get_name('firstname') }}</h1>
                    @else
                        @if(request()->segment(1) == 'password')
                                <h1 class="f-two f-38 f-bold c-white u-mb0 u-mt0">Reset your password</h1>
                        @endif
                    @endif
                    <div class="generic-border u-center u-mt05"></div>
                </div>
            </div>
        </div>
    </div>
</section>
