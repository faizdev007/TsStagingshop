@extends('layouts.front.app')
@section('content')
<!-- inner-hero -->
<section class="inner-hero">
    <div class="container">
        @include('partials.front.pw.breadcrumb')
        <h1 class="f-two" data-aos="fade-right">GLOBAL LUXURY SEARCH</h1>
    </div>
</section>
<!-- End inner-hero -->

<!-- About -->
<section class="container-fluid">
	<div id='reciprocity'></div>
</section>
<!-- End About -->
@endsection

@push('frontend-footer-scripts-end')
<script src="/assets/pw/libraries/jquery/jquery-3.6.0.min.js"></script>
<script src="/assets/pw/libraries/masonry.pkgd.min.js"></script>
<script type="text/javascript">
    $('.grid').masonry({
        // options
        itemSelector: '.grid-item',
        columnWidth: 1
    });
</script>
<script src='https://conradvillas.luxuryrealestate.com/reciprocity.js' type='text/javascript'></script>
<script type='text/javascript'>
  Reciprocity.init({
    membersite: "conradvillas",
    parentLocation: window.location
  });
  window.onload = function() {
	let iframe = document.querySelector('iframe');
	iframe.setAttribute('height', 'auto');
  }
</script>
@endpush
