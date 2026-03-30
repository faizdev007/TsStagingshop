@push('body_class')home-page @endpush

@extends('frontend.demo1.layouts.frontend')

@section('main_content')
    @switch($template)
        @case(2)
            @include('frontend.demo1.page-templates.homepage-2')
            @break;
        @case(3)
            @include('frontend.demo1.page-templates.homepage-3')
            @break;
        @default
            @include('frontend.demo1.page-templates.homepage-1')
    @endswitch
@endsection
