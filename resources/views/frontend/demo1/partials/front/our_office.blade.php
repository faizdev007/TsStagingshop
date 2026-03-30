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
	}
	.office_row{
		padding-bottom:20px;
		justify-content: space-between;
		display: flex;
	}
	.office_row .img_cont {
		display:inline-block;
		width:31.5%;
		text-align:center;		
		height:200px;
	}
	
	.office_row .img_cont div {
		height:100%;
		width:100%;
		background-size: cover;
	}
	.img_center {
		padding-left:30px;
		padding-right:30px;
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
		.office_row {
			padding: 20px;
			display: block;
		}
		.office_row .img_cont {
			width:100%;
			padding:15px;
			height:533px;
		}
		.office_row{
			padding:0px 10px 0px 10px;
			
		}
		.about-block {
			padding-bottom:20px;
		}
	}
	@media only screen and (max-width: 500px) {
		.office_row .img_cont {
			height:240px;
		}
	}
	
</style>
<div class="about-block">	
	<div class="centering">
		<h1>Our Office</h1>
		
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
				<div style="background-image:url({{$img}})"></div>
			</div>	
			
			<?php if ($iteration==3) {
				$iteration = 0;
				?>
				</div>
			<?php
			}?>
			
		<?php
		}?>
		
		
		<!---
		<div class="office_row">
			<div class="img_cont">
				<div style="background-image:url(https://v6e3y9j9.stackpathcdn.com/uploads/properties/1382/the-ritz-carlton-luxury-sky-residence-for-sale-in-bangkok-dQy6VIri0s9Hj4cpd7XajbeWueGolFdu.jpg)"></div>
			</div>	
			<div class="img_cont ">
				<div style="background-image:url(https://v6e3y9j9.stackpathcdn.com/uploads/properties/1382/the-ritz-carlton-luxury-sky-residence-for-sale-in-bangkok-dQy6VIri0s9Hj4cpd7XajbeWueGolFdu.jpg)"></div>
			</div>	
			<div class="img_cont">
				<div style="background-image:url(https://v6e3y9j9.stackpathcdn.com/uploads/properties/1382/the-ritz-carlton-luxury-sky-residence-for-sale-in-bangkok-dQy6VIri0s9Hj4cpd7XajbeWueGolFdu.jpg)"></div>
			</div>	
		</div>	
		-->
	</div>	
</div>	

@endsection
