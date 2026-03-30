@push('body_class')property-page @endpush

@extends('frontend.demo1.layouts.frontend')

@section('main_content')

    @switch($template)
        @case(2)
            @include('frontend.demo1.page-templates.property-2')
            @break;
        @default
            @include('frontend.demo1.page-templates.property-1')
    @endswitch

@endsection
