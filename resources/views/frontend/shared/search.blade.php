@push('body_class')search-page @endpush

@extends('frontend.demo1.layouts.frontend')

@section('main_content')

    @switch($template)
        @case('shortlist')
        @include('frontend.demo1.page-templates.search-shortlist')
        @break;
        @default
        @include('frontend.demo1.page-templates.search')
    @endswitch

    @if(0)
        @if(isset($posts) && isset($criteria['in']))
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h3>News Articles Related to Property in the '{{ $criteria['in'] }}' Area</h3>
                    </div>
                </div>
                @include('frontend.shared.area-related-news')
            </div><!-- /.container -->
        </section>
        @endif
    @endif

    <!-- Search Content (Optional depending on Config) -->
    @include('frontend.shared.search-content')

@endsection
