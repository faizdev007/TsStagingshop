@php $counters = get_top_counter(); @endphp

<style>


.col-lg-2 {
    width: 13.66666667%;
  }


</style>    


<div class="counter-collapse">
    <a href="#" class="cta-collapse" data-toggle="collapse" data-target="#title-counter">View Counters <span class="fa fa-chevron-down"></span></a>
</div>
<!-- counters -->
<div id="title-counter" class="row tile_count collapse">
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-6 tile_stats_count total-properties">
        <span class="count_top"><a href="{{admin_url('properties')}}"><i class="fa fa-home"></i> Total Properties</a></span>
        <div class="count">{{ $counters['totalProperties'] }}</div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-6 tile_stats_count active-properties">
        <span class="count_top"><a href="{{admin_url('properties?status=2&search=yes')}}"><i class="fa fa-home"></i> Active Properties</a></span>
        <div class="count green">{{ $counters['totalActiveProperties'] }}</div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-6 tile_stats_count in-active-properties">
        <span class="count_top"><a href="{{admin_url('properties?status=-1&search=yes')}}"><i class="fa fa-home"></i> Inactive Properties</a></span>
        <div class="count">{{ $counters['totalInActiveProperties'] }}</div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-6 tile_stats_count enquiries">
        <span class="count_top"><a href="{{ adminAlterLink($counters['sevenDaysEnquiries'], 'enquiries?sevenDays=1', 'enquiries') }}"><i class="fa fa-envelope"></i> Enquiries</a></span>
        <div class="count green">{{ $counters['sevenDaysEnquiries'] }}</div>
        <span class="count_bottom"><i class="green-v2"><i class="fa fa-sort-desc"></i></i> in the last <i class="green-v2">7 days</i></span>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-6 tile_stats_count enquiries-v2">
        <span class="count_top"><a href="{{ adminAlterLink($counters['thirtyDaysEnquiries'], 'enquiries?thirtyDays=1', 'enquiries') }}"><i class="fa fa-envelope"></i> Enquiries</a></span>
        <div class="count">{{ $counters['thirtyDaysEnquiries'] }}</div>
        <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i></i> in the last <i class="green">30 days</i></span>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-6 tile_stats_count enquiries-v2">
        <span class="count_top"><a href="{{ adminAlterLink($counters['totalwhatsappclicks'], 'whatsapp', 'whatsapp') }}"><i class="fas fa-mouse-pointer"></i> Whatsapp Clicks</a></span>
        <div class="count green">{{ $counters['totalwhatsappclicks'] }}</div>
        
    </div>
    
    @if(  Auth::user()->role_id == '1' && settings('show_subscribers') )
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-6 tile_stats_count total-subscribers">
        <span class="count_top "><a href="{{admin_url('subscribers')}}"><i class="fa fa-envelope"></i> Total Subscribers</a></span>
        <div class="count green">{{ $counters['totalSubscriber'] }}</div>
    </div>
    @endif

</div>
<!-- /counters -->
