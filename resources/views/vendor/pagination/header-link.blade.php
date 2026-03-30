@if ($paginator->hasPages())

    @if (!$paginator->onFirstPage())
    <link rel="prev" href="{{ $paginator->previousPageUrl() }}" />@endif

    @if ($paginator->hasMorePages())
    <link rel="next" href="{{ $paginator->nextPageUrl() }}" />@endif

@endif

@if( Route::getCurrentRoute()->getActionName() == 'App\Http\Controllers\Frontend\PropertiesController@index')
    @php
        $segments = [];
        $segments[] = request()->segment(1);
        if( request()->segment(2) == 'in' ):
            $segments[] = 'in';
            $segments[] = request()->segment(3);
        endif;
        if(!empty(request('page'))):
            $segments[] = '?page='.request('page');
        endif;
        $segmentUrl = implode('/',$segments);
    @endphp
    <link rel="canonical" href="{{ url( $segmentUrl ) }}"/>
@else
    <link rel="canonical" href="{{url()->full()}}"/>
@endif
