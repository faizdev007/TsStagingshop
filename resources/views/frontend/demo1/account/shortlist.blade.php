@extends('frontend.demo1.layouts.frontend')
@section('main_content')
    @include('frontend.demo1.account.parts.banners')
    <section class="u-pt0 u-pb2">
        <div class="container">
                @if (session('message_success'))
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-success">
                                {{ session('message_success') }}
                            </div>
                        </div>
                    </div>
                @endif
            @include('frontend.demo1.account.nav.nav')
            <div class="row u-mt1">
                <div class="col">
                    @if($shortlist->count() > 0 )
                        <h4 class="f-24 f-bold u-block u-mb1">{{ count($shortlist) }} Properties Shortlisted</h4>
                        <div class="row property-list-style-3">
                            @foreach($shortlist as $sh)
                                <div id="p-{{ $sh->property->id }}" class="col-md-4 col-sm-6 u-mt1 u-mb1">

                                    <div class="property-item">

                                        <div class="-remove-btn">
                                            <a href="#"
                                            class="f-15 shortlist-add {{ ($sh->property->CheckShortlistIp) ? 'shortlist-confirm-action' : '' }}"
                                            data-url="{{ url('shortlist/ajax/add') }}"
                                            data-property-id="{{ $sh->property->id }}"
                                            data-save-text="&nbsp;{{trans_fb('app.app_Save', 'SAVE')}}&nbsp;"
                                            data-remove-text="&nbsp;{{trans_fb('app.app_Remove', 'REMOVE')}}&nbsp;"
                                            ><i class="fas {{ ($sh->property->CheckShortlistIp) ? "fa-times" : "fa-star" }}"></i> {{ ($sh->property->CheckShortlistIp) ? trans_fb('app.app_Remove', 'REMOVE') : trans_fb('app.app_Save', 'SAVE') }}</a>
                                        </div>

                                        @if( count($sh->property->propertyMediaPhotos) )
                                            <div class="grid-image">
                                                @php $i = 0; @endphp
                                                @foreach( $sh->property->propertyMediaPhotos as $media )
                                                    @php $i++; @endphp

                                                    <div class="f-image-box">

                                                        @if($i==1)
                                                            <div class="f-image {{ ($media->orientation == 'portrait' ? '' : 'fill') }}">
                                                                <span></span>
                                                                <a href="{{$sh->property->url}}">
                                                                    <img class="b-lazy" src="{{ blankImg() }}" data-src="{{ $media->photo_display }}" alt="{{$sh->property->image_alt}} - {{$i}}">
                                                                </a>
                                                            </div>
                                                        @else
                                                            <div class="f-image {{ ($media->orientation == 'portrait' ? '' : 'fill') }}">
                                                                <span></span>
                                                                <a href="{{$sh->property->url}}">
                                                                    <img src="{{ blankImg() }}" data-lazy="{{ $media->photo_display }}" alt="{{$sh->property->image_alt}} - {{$i}}">
                                                                </a>
                                                            </div>
                                                        @endif

                                                        @if ($sh->property->property_status)
                                                            <div class="f-status">
                                                                <span class="c-white c-bg-primary f-semibold u-inline-block f-13 f-two text-uppercase">{{ $sh->property->property_status }}</span>
                                                            </div>
                                                        @endif
                                                    </div>

                                                @endforeach
                                            </div>
                                        @else
                                            <div class="grid-image">
                                                <div class="f-image-box">
                                                    <div class="f-image">
                                                        <span></span>
                                                        <a href="{{ url($sh->property->url) }}">
                                                            <img class="b-lazy" src="{{ blankImg() }}" data-src="{{ default_thumbnail() }}" alt="{{$sh->property->image_alt}}"></a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="f-body">
                                            <div class="f-title">
                                                <div class="f-main-title"><h2 class="f-18 f-semibold fix-line">{{ $sh->property->search_headline }}</h2></div>
                                                <div class="f-13 f-italic c-lighter fix-line">{{ $sh->property->DisplayPropertyAddress }}</div>
                                            </div>
                                            <div class="f-price f-18 f-bold">{!! $sh->property->display_price !!}</div>
                                            <div class="f-attr">{!! $sh->property->BedBathArea !!}</div>
                                            <div class="f-cta">
                                                <a href="{{$sh->property->url}}" class="button -primary f-14 f-semibold u-inline-block text-uppercase">See Details</a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="pagination-bottom">
                            {{ $shortlist->links() }}
                        </div>
                    @else
                        <p>You currently have no items in your shortlist. <a class="f-bold c-primary" href="{{url('property-for-sale')}}">Start your property search now!</a></p>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
