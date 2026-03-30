<div class="pl-2 pr-2">
    <div class="row">
        <div class="col">
            <h5 class="d-block mb-4">Please choose a date</h5>
        </div><!-- /.col -->
    </div><!-- /.row -->
    <div class="row">
        <div class="col">
            <div class="text-center">
                <strong class="d-block mb-4">{{ $months[0] }}
                    @if($months[0] !== $months[1])
                        @if(!empty($months[1]))
                            / {{ $months[1] }}
                        @endif
                    @endif
                </strong>
            </div><!-- /.text-center -->
        </div><!-- /.col -->
    </div><!-- /.row -->
    <div class="row seven-cols">
        @foreach($dates as $date)
            <div class="col-3 col-md-1 mb-4">
                <div class="text-center">
                    <div class="d-block">
                        {{ $date['day'] }}
                    </div>
                    <a class="d-inline-block p-2 data-select-block w-100 date-selection" href="#" data-date="{{ $date['date_friendly'] }}">
                        {{ $date['date'] }}
                    </a>
                </div><!-- /.text-center -->
            </div><!-- /.col-3 -->
        @endforeach
    </div><!-- /.row -->
{{--    <!--div class="row">--}}
{{--        <div class="col">--}}
{{--            <div class="text-center mt-1 mb-1">--}}
{{--                <h4 class="chosen-date">&nbsp;</h4>--}}
{{--            </div><!-- /.text-center -->--}}
{{--        </div><!-- /.col -->--}}
{{--    </div><!-- /.row -->--}}
    <div class="row">
        <div class="col">
            <h5 class="d-block mb-4">Please choose 3 times</h5>
        </div><!-- /.col -->
    </div><!-- /.row -->
    <div class="row">
        @foreach($times as $time)
            @php
              $search = array(':', 'am', 'pm');
            @endphp
            <div class="col-4 col-sm-2 mb-4">
                <a data-id="{{ date("H:i", strtotime($time)) }}" data-time="{{ $time }}" class="data-select-block time-block p-2 d-inline-block w-100" href="#">
                    <div class="text-center">
                        {{ $time }}
                    </div>
                </a>
            </div><!-- /.col-3 -->
        @endforeach
    </div><!-- /.row -->
     <div class="chosen-select mt-4 mb-4">
        <div class="row">
            <div class="col">
                <div class="text-center">
                    <h4 class="chosen-text f-18 d-block mb-2">Your selected date &amp; time is <strong class="chosen-date"></strong> <strong class="at-time" style="display: none;">at</strong> <strong class="chosen-times"></strong></h4>
                </div><!-- /.text-center -->
            </div><!-- /.col -->
        </div><!-- /.row -->
     </div><!-- /.chosen-date -->
    <div class="user-details mt-4">
        <div class="row">
            <div class="col">
                <h5 class="d-block mb-4">Your Details</h5>
            </div><!-- /.col -->
        </div><!-- /.row -->
        <div class="success" style="display: none;">
            <div class="row">
                <div class="col-12">
                    <div class="text-center">
                        <div class="success-message"></div>
                    </div><!-- /.text-center -->
                </div><!-- /.col-12 -->
            </div><!-- /.row -->
        </div>
        <div class="errors" style="display: none;">
            <div class="row">
                <div class="col-12">
                    <div class="text-center">
                        <div class="error-message c-red"></div>
                    </div><!-- /.text-center -->
                </div><!-- /.col-12 -->
            </div><!-- /.row -->
        </div>
        <div class="row">
            <form id="ajax-form-valuation" method="POST" action="" class="pl-3 pr-3 mt-4 mb-4 mt-4 mb-4 w-100" data-toggle="validator">
                <div class="form-row">
                    <div class="col-12 col-sm-6">
                        <div class="form-group mr-0 mr-sm-2">
                            <input class="form-control form__input name" type="text" name="fullname" placeholder="Full Name*" @if(settings('members_area') == '1') @if(Auth::user()) value="{{ Auth::user()->name }}" readonly="readonly" @endif @endif required autocomplete="off">
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div><!-- /.form-group -->
                    </div><!-- /.col-sm-6 -->
                    <div class="col-12 col-sm-6">
                        <div class="form-group">
                            <input type="email" class="form-control form__input email" name="email" placeholder="Email Address*" @if(settings('members_area') == '1') @if(Auth::user()) value="{{ Auth::user()->email }}" readonly="readonly" @endif @endif required autocomplete="off">
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div><!-- /.form-group -->
                    </div><!-- /.col-sm-6 -->
                </div><!-- /.form-row -->
                <div class="form-row">
                    <div class="col-12">
                        <div class="form-group">
                            <input name="telephone" type="tel" class="form-control form__input phone" placeholder="Telephone Number" autocomplete="off">
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div><!-- /.form-group -->
                    </div><!-- /.col-sm-6 -->
                </div><!-- /.form-row -->
                <div class="form-row">
                    <div class="col-12">
                        <div class="text-center">
                            <button type="submit" class="button -primary submit-viewing">Submit</button>
                        </div><!-- /.text-center -->
                    </div><!-- /.col-12 -->
                </div><!-- /.form-row -->
                <input type="hidden" class="date" value="">
                <input type="hidden" class="times" value="">
            </form>
        </div><!-- /.row -->
    </div><!-- /.user-details -->
</div><!-- /.row -->
