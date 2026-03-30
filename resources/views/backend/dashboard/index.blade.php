@extends('backend.layouts.master')

@section('admin-content')
@php $counters = get_top_counter(); @endphp
    <div class="row">
      <div class="col-md-12">
        <h2>Last 5 Login User</h2>
      </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-sm-4 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Properties</h2>
              <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li class="dropdown"></li>
              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <div class="">
                <ul class="to_do pw">
                  <li>
                    <a href="{{ adminAlterLink($counters['sevenDaysProperties'], 'properties?sevenDays=1&search=yes', 'properties') }}"><i class="fa fa-caret-right"></i> Properties added in the last 7 days</a>
                  </li>
                  <li>
                    <a href="{{admin_url('properties?status=2&search=yes')}}"><i class="fa fa-caret-right"></i> Active Properties</a>
                  </li>
                  <li>
                    <a href="{{admin_url('properties?status=-1&search=yes')}}"><i class="fa fa-caret-right"></i> Inactive Properties</a>
                  </li>
                  <li>
                    <a href="{{ adminAlterLink($counters['propertiesHas3Enquiries'], 'properties?popular=1&search=yes', 'properties') }}"><i class="fa fa-caret-right"></i> Popular Properties</a>
                  </li>
                  <li>
                    <a href="{{admin_url('properties?status=1&search=yes')}}"><i class="fa fa-caret-right"></i> Archived Properties</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        @if(  Auth::user()->role_id == '1' || Auth::user()->role_id == '2')
        <div class="col-md-4 col-sm-4 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Management</h2>
              <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li class="dropdown"></li>
              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <div class="">
                <ul class="to_do pw">
                  <li>
                    <a href="{{admin_url('slides')}}"><i class="fa fa-caret-right"></i> Home Page Sliders</a>
                  </li>

                  @if(!settings('propertybase'))
                  <li>
                    <a href="{{admin_url('communities')}}"><i class="fa fa-caret-right"></i> Communities</a>
                  </li>
                  @endif
                  
                  <li>
                    <a href="{{admin_url('news')}}"><i class="fa fa-caret-right"></i> Blog</a>
                  </li>
                  <li>
                    <a href="{{admin_url('postcategories')}}"><i class="fa fa-caret-right"></i> Blog Categories</a>
                  </li>
                  <li>
                    <a href="{{admin_url('pages')}}"><i class="fa fa-caret-right"></i> Pages</a>
                  </li>
                  <li>
                    <a href="{{admin_url('pages/1/edit')}}"><i class="fa fa-caret-right"></i> Home Page</a>
                  </li>
                  <li>
                    <a href="{{admin_url('users')}}"><i class="fa fa-caret-right"></i> Users</a>
                  </li>
                  @if(settings('members_area') == 1)
                    <li>
                      <a href="{{admin_url('members')}}"><i class="fa fa-caret-right"></i> Members</a>
                    </li>
                  @endif
                </ul>
              </div>
            </div>
          </div>
        </div>
        @endif

        <div class="col-md-4 col-sm-4 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Reporting</h2>
              <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li class="dropdown"></li>
              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <div class="">
                <ul class="to_do pw">
                  <li>
                    <a href="{{admin_url('enquiries')}}"><i class="fa fa-caret-right"></i> All Enquiries</a>
                  </li>
                  <li>
                    <a href="{{ adminAlterLink($counters['sevenDaysEnquiries'], 'enquiries?sevenDays=1', 'enquiries') }}"><i class="fa fa-caret-right"></i> Enquiries from last 7 days</a>
                  </li>
                  <li>
                    <a href="{{ adminAlterLink($counters['thirtyDaysEnquiries'], 'enquiries?thirtyDays=1', 'enquiries') }}"><i class="fa fa-caret-right"></i> Enquiries from last 30 days</a>
                  </li>
                  <li>
                    <a href="{{ adminAlterLink($counters['totalwhatsappclicks'], 'whatsapp', 'whatsapp') }}"><i class="fa fa-caret-right"></i> Whatsapp Clicks</a>
                  </li>
                  <li>
                    <a href="{{ adminAlterLink($counters['propertiesHas3Enquiries'], 'properties?popular=1&search=yes', 'properties') }}"><i class="fa fa-caret-right"></i> Popular Properties</a>
                  </li>
                  @if(  Auth::user()->role_id == '1' && settings('show_subscribers') )
                  <li>
                    <a href="{{admin_url('subscribers')}}"><i class="fa fa-caret-right"></i> Subscribers</a>
                  </li>
                  @endif
                </ul>
              </div>
            </div>
          </div>
        </div>

    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                fetch('/runJobs').catch(() => {});
            }, 10000);
        });
    </script>

@endsection
