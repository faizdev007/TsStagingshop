@extends('layouts.front.all')
@section('content')
<!-- inner-hero -->
<section class="inner-hero">
	<div class="container">
		@include('partials.front.pw.breadcrumb')
		<h1 class="f-two text-uppercase" data-aos="fade-right">Our Partners</h1>
	</div>
</section>
<!-- End inner-hero -->
<section class="blog-wrapper u-circle-style-1 u-circle-style-2">
	<div class="container">
		<div class="blog-details-wrp">
			<div class="partners--wrap">
				<div class="row d-flex align-items-center">
					@foreach($images AS $img)
						<div class="col-lg-3 col-md-4 col-6">
							<div class="partners--item text-center d-flex align-items-end justify-content-center u-min-height-150 u-min-height-md-0 mb-5">
								<img src="{{dev_domain()}}{{$img}}" class="u-width-full mb-0 u-mh-120 u-mw-160">
							</div>
						</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
