@if (isset($community) && $community->is_publish == 1)
    <section class="property-deta-wrp u-circle-style-1">
        <div class="u-circle-style-2">
            <div class="container">
                <div class="position-relative">
                    <div class="generic-header--section -cta ">
                        <h3 class="generic-header--large f-25 f-xlg-45 f-md-20 f-one" data-aos="fade-right" style="font-family: Cormorant, sans-serif; font-weight: 600;">About {{$community->name}}</h3>                
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="prod-data-slider">
                            @if(!empty($community->photos) && is_array($community->photos) && count($community->photos) > 1)
                                <div class="products-slider">
                                    <div class="cSlider cSlider--single">
                                        @php $i = 0; @endphp
                                        @foreach( $community->photos as $media )
                                        @php $i++; @endphp
                                            <div class="cSlider__item">
                                                <div class="big-img-bx">
                                                    <div class="big-img-bx--inner go-center fill">
                                                        <a href="{{ storage_url($media)  }}" data-thumb="{{$media}}" data-fancybox="community" data-type="image">
                                                            <img src="{{ blankImg() }}" data-lazy="{{ storage_url($media)  }}" alt="Photo thumbs - {{$i}}">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="cSlider cSlider--nav u-dnone-500">
                                        @foreach( $community->photos as $media )
                                        <div class="cSlider__item">
                                            <div class="small-img-bx ratio ratio-16x9">
                                                <img src="{{ storage_url($media)  }}" alt="{{($community->name . '- thumb '.$loop->iteration)}}">
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                @if(!empty($community->photo))
                                    <div class="products-slider mb-4">
                                        <div class="cSlider cSlider--single">
                                            <div class="cSlider__item">
                                                <div class="big-img-bx">
                                                    <div class="big-img-bx--inner go-center fill">
                                                        <a href="{{ storage_url($community->photo) }}" data-thumb="{{storage_url($community->photo)}}" data-fancybox="community" data-type="image">
                                                            <img src="{{ storage_url($community->photo) }}" data-lazy="{{ storage_url($community->photo)  }}" alt="Photo thumbs">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                {!!$community->content!!}
                </div>
            </div>
        </div>
    </section>
@else
<div class="u-mb1"></div>
@endif