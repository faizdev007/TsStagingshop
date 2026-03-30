@php $current_tab = str_replace('-',' ',request()->segment(2)); @endphp
<div class="row">
    <div class="col">
        <div class="burger-box u-mt2 u-mb2">
            {{-- <div class="mobile">
                <a class="button -primary -square u-block-mobile text-uppercase f-bold c-white toggle-link u-pt05 u-pb05" data-target="account-tabs" href="#">
                    <i class="fas fa-caret-down"></i> {{ ($current_tab)?$current_tab:'account'}}
            </a>

        </div> --}}
        <div class="desktop account-tabs justify-content-between">
            <div class="flex-lg-fill">
                <a class="account-item @if(request()->segment(1) == 'account' && request()->segment(2) == '') -active @endif" href="{{ url('account') }}" title="Account">
                    <div class="text-md-center">
                        <span class="u-block f-22">
                            <i class="fas fa-user"></i>
                        </span>
                        <span class="u-block d-none d-md-block f-15 account-item__title">Account</span>
                    </div>
                </a>
            </div>
            <div class="flex-lg-fill">
                <a class="account-item @if(request()->segment(2) == 'shortlist') -active @endif" href="{{ url('account/shortlist') }}" title="Shortlist">
                    <div class="text-md-center">
                        <span class="u-block f-22">
                            <i class="fas fa-heart"></i>
                        </span>
                        <span class="u-block d-none d-md-block f-15 account-item__title">Shortlist</span>
                    </div>
                </a>
            </div>
            <div class="flex-lg-fill">
                <a class="account-item @if(request()->segment(2) == 'saved-searches') -active @endif" href="{{ url('account/saved-searches') }}" title="Saved Searches">
                    <div class="text-md-center">
                        <span class="u-block f-22">
                            <i class="fas fa-star"></i>
                        </span>
                        <span class="u-block d-none d-md-block f-15 account-item__title">Saved Searches</span>
                    </div>
                </a>
            </div>
            <div class="flex-lg-fill">
                <a class="account-item @if(request()->segment(2) == 'property-alerts') -active @endif" href="{{ url('account/property-alerts') }}" title="Property Alerts">
                    <div class="text-md-center">
                        <span class="u-block f-22">
                            <i class="fas fa-home"></i>
                        </span>
                        <span class="u-block d-none d-md-block f-15 account-item__title">Property Alerts</span>
                    </div>
                </a>
            </div>
            <div class="flex-lg-fill">
                <a class="account-item @if(request()->segment(2) == 'notes') -active @endif" href="{{ url('account/notes') }}" title="Property Notes">
                    <div class="text-md-center">
                        <span class="u-block f-22">
                            <i class="fas fa-pencil-alt"></i>
                        </span>
                        <span class="u-block d-none d-md-block f-15 account-item__title">Property Notes</span>
                    </div>
                </a>
            </div>
            <div class="flex-lg-fill">
                <a class="account-item @if(request()->segment(2) == 'messages') -active @endif" href="{{ url('account/messages') }}" title="Messages">
                    <div class="text-md-center">
                        <span class="u-block f-22">
                            <i class="fas fa-envelope"></i>
                        </span>
                        @if($unread >= 1)
                        <span class="u-absolute unread-messages c-bg-primary"><span class="c-white">{{ $unread }}</span></span>
                        @endif
                        <span class="u-block d-none d-md-block f-15 account-item__title">Messages</span>
                    </div>
                </a>
            </div>
            <!-- <div class="flex-lg-fill">
                    <a class="account-item @if(request()->segment(2) == 'messages') -active @endif" href="{{ url('account/messages') }}">
                        <div class="text-md-center">
                    <span class="u-block f-22">
                        <i class="fas fa-envelope"></i>
                    </span>
                            @if($unread >= 1)
                                <span class="u-absolute unread-messages c-bg-primary"><span class="c-white">{{ $unread }}</span></span>
                            @endif
                            <span class="u-block f-15 account-item__title">Off Plan Property</span>
                        </div>
                    </a>
                </div> -->
        </div>
    </div>
</div>
</div>