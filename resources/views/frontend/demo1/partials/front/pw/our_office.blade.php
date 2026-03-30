@extends('layouts.front.all')

@push('frontend-footer-style')
<link rel="stylesheet" href="{{mix('assets/pw/css/conrad-inner.css')}}" type="text/css">
@endpush

@section('content')
<!-- inner-hero -->
<section class="inner-hero">
	<div class="container">
		@include('partials.front.pw.breadcrumb')
		<h1 class="f-two text-uppercase" data-aos="fade-right">Our Office</h1>
	</div>
</section>
<section class="blog-wrapper u-circle-style-1 u-circle-style-2">
	<div class="container">
        <div class="blog-details-wrp">
			<?php
			$iteration = 0;
			foreach ($images AS $img) {
				$iteration++;
				if ($iteration==1) {
					?>
					<div class="office_row">
				<?php
				}?>
				
				<div class="img_cont">
					<div style="background-image:url(' {{ asset($img) }} ')"></div>
				</div>

				<?php if ($iteration==3) {
					$iteration = 0;
					?>
					</div>
				<?php
				}?>

			<?php
			}?>
        </div>
	</div>
</section>

@endsection
