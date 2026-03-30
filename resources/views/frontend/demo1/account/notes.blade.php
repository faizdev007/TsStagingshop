@extends('frontend.demo1.layouts.frontend')
@push('body_class')property-alerts @endpush
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
                        @if($notes->count() > 0 )
                            <h4 class="f-24 f-bold u-block u-mb1">{{ count($notes) }} Property Notes</h4>
                            <div class="row property-list-style-3">
                                @foreach($notes as $note)
                                    @if($note->property)
                                        <div id="note-{{ $note->note_id }}" class="col-md-4 col-sm-6 u-mt1 u-mb1">
                                        <div class="property-item">
                                            @if( count($note->property->propertyMediaPhotos) )
                                                <div class="grid-image">
                                                    @php $i = 0; @endphp
                                                    @foreach( $note->property->propertyMediaPhotos as $media )
                                                        @php $i++; @endphp

                                                        <div class="f-image-box">

                                                            @if($i==1)
                                                                <div class="f-image {{ ($media->orientation == 'portrait' ? '' : 'fill') }}">
                                                                    <span></span>
                                                                    <a href="{{$note->property->url}}">
                                                                        <img class="b-lazy" src="{{ blankImg() }}" data-src="{{ $media->photo_display }}" alt="{{$note->property->image_alt}} - {{$i}}">
                                                                    </a>
                                                                </div>
                                                            @else
                                                                <div class="f-image {{ ($media->orientation == 'portrait' ? '' : 'fill') }}">
                                                                    <span></span>
                                                                    <a href="{{$note->property->url}}">
                                                                        <img src="{{ blankImg() }}" data-lazy="{{ $media->photo_display }}" alt="{{$note->property->image_alt}} - {{$i}}">
                                                                    </a>
                                                                </div>
                                                            @endif

                                                            @if ($note->property->property_status)
                                                                <div class="f-status">
                                                                    <span class="c-white c-bg-primary f-semibold u-inline-block f-13 f-two text-uppercase">{{ $note->property->property_status }}</span>
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
                                                            <a href="{{ url($note->property->url) }}">
                                                                <img class="b-lazy" src="{{ blankImg() }}" data-src="{{ default_thumbnail() }}" alt="{{$note->property->image_alt}}"></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="f-body">
                                                <h2 class="f-16 c-dark f-black">Noted on: {{ $note->friendly_date }}</h2>
                                                <span class="street f-14">{{ $note->property->search_headline  }}</span>
                                                <span class="street f-14">{{ $note->property->FsAddress1 }}</span>
                                                <span class="town text-uppercase u-block f-12">{{ $note->property->FsAddress2 }}</span>
                                                <div class="u-pt1 u-pb1 u-mb0 f-13">
                                                    {!! $note->shortened_text !!}
                                                </div>
                                                @if (strlen($note->note_content) <= 150)
                                                @else
                                                    <a class="f-13 c-tertiary -underline u-inline-block u-mt0 u-mb1" data-toggle="modal" data-target="#note-modal-{{ $note->note_id }}" href="#">Read more</a>
                                                @endif
                                                <div class="u-mt1 u-mb05">
                                                    <a class="button -primary f-14 u-block-mobile f-semibold modal-toggle" href="#"
                                                       data-item-id="{{ $note->note_id }}"
                                                       data-toggle="modal"
                                                       data-modal-type="delete"
                                                       data-modal-title="Delete Note"
                                                       data-modal-size="small"
                                                       data-delete-type="note"
                                                       data-target="#global-modal">
                                                        <i class="fas fa-times c-white"></i>
                                                        <span class="shortlist-text-label">REMOVE</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="note-modal-{{ $note->note_id }}" class="note-modal modal fade" tabindex="-1" role="dialog">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header u-relative">
                                                        <button type="button" class="close-button" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body u-pl0 u-pr0">
                                                        <div class="property-photo">
                                                            @if ($note->property->property_status)
                                                                <span class="tag c-bg-primary c-white f-semibold f-14 u-absolute">{{ $note->property->property_status }}</span>
                                                            @endif
                                                            <span class="price u-absolute c-bg-secondary c-white f-16">{!! $note->property->display_price !!}</span>
                                                            @if( count($note->property->propertyMediaPhotos) )
                                                                <div>
                                                                    @php $i = 0; @endphp
                                                                    @foreach( $note->property->propertyMediaPhotos->take(1) as $media )
                                                                            <div>
                                                                                <div class="slide-image-container">
                                                                                    <span></span>
                                                                                    <img class="img-fluid" src="{{ $media->photo_display }}" alt="{{$note->property->image_alt}} - {{$i}}">
                                                                                </div>
                                                                            </div>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <div>
                                                                    <div>
                                                                        <div class="slide-image-container">
                                                                            <a href="{{ url($note->property->url) }}"><img src="{{ default_thumbnail() }}" alt="{{$note->property->image_alt}} - 1"></a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="property-content">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="u-pl1 u-pr1">
                                                                        <h2 class="f-14 c-dark f-black">Noted on: {{ $note->friendly_date }}</h2>
                                                                        <div class="u-pt1 u-pb1 u-mb1 f-13">
                                                                            {!! $note->note_content !!}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="pagination-bottom">
                                {{ $notes->links() }}
                            </div>
                        @else
                            <p>You currently have no notes. <a class="f-bold c-primary" href="{{url('property-for-sale')}}">Start adding some now!</a></p>
                        @endif
                    </div>
                </div>
        </div>
    </section>

@endsection
