@extends('layouts.front.all')

@section('content')
<style>
	.about-block h1 {
		padding-right: 120px;
		margin-bottom: 35px;
		font-family: 'GothamBlack';
	}

	h1 {
		padding-top: 20px;
		position: relative;
		padding-left: 67px;
		padding-bottom: 40px;
		margin-top: -52px;
		background: #fff;
		margin-left: -67px;
		margin-bottom:0px !important;
	}
	.partner_row{
		padding:50px;
		
	}
	.partner_row .img_cont {
		display:inline-block;
		width:24%;
		text-align:center;		
	}
	.partner_row .img_cont img {
		display:inline-block;
		width:160px;
	}
	
	
	@media only screen and (max-width: 800px) {
		h1{
			padding-top: 20px;
			position: relative;		
			padding-left: 67px;
			padding-bottom: 20px;		
			margin-top:-52px;
			background:none;
			margin-left: 0px;
			margin-bottom:10px !important;
		}
		.partner_row {
			padding: 20px;
		}
		.partner_row .img_cont {
			width:49%;
			padding:15px;
		}
		.partner_row{
			padding:0px 10px 0px 10px;
			
		}
		.about-block {
			padding-bottom:20px;
		}
	}
</style>
<div class="about-block">	
	<div class="centering">
		<h1>Our Partners</h1>
		
		<?php
		$iteration = 0;
		foreach ($images AS $img) {
			$iteration++;
			if ($iteration==1) {
				?>
				<div class="partner_row">
			<?php
			}?>
			
			<div class="img_cont">
				<img src="{{$img}}">
			</div>	
			
			<?php if ($iteration==4) {
				$iteration = 0;
				?>
				</div>
			<?php
			}?>
			
		<?php
		}?>
		
		<!--
		<div class="partner_row">
			<div class="img_cont">
				<img src="/assets/images/partner.png">
			</div>
			<div class="img_cont">
				<img src="/assets/images/partner.png">
			</div>
			<div class="img_cont">
				<img src="/assets/images/partner.png">
			</div>
			<div class="img_cont">
				<img src="/assets/images/partner.png">
			</div>			
		</div>	
		-->
	</div>	
</div>	

@endsection
