@if($latest_properties->count() > 0)
    <section id="{{ $page_section->url }}">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-center">
                        <h3 class="f-bold f-22 u-mb2">{{ $page_section->title }}</h3>
                    </div><!-- /.text-center -->
                </div><!-- /.col-lg-12 -->
            </div><!-- /.row -->
            <div class="property-slides">
                @foreach($latest_properties as $property)
                    <div class="w-100">
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 order-md-first order-lg-first order-last">
                                <strong class="f-20 u-block">{{ $property->details_headline }}, {{ $property->FsAddress1 }}</strong>
                                <span class="f-13 f-bold c-secondary text-uppercase u-block">{{ $property->FsAddress1 }} @if($property->postcode), {{ $property->postcode }} @endif</span>
                                <div class="u-dividing-line c-bg-secondary u-mt2 u-mb2"></div>
                                {!! Str::limit($property->description, 250) !!}
                                <div class="u-mt2 u-mb2">
                                    <a class="button -tertiary text-uppercase f-bold" href="{{ url($property->url) }}">View Property</a>
                                </div>
                                <div class="u-block u-mb1">&nbsp;</div>
                            </div>
                            <div class="col-lg-6 col-sm-12 order-md-last order-lg-last order-first">
                                @foreach( $property->propertyMediaPhotos->take(1) as $media )
                                    <a href="{{ $property->url }}">
                                        <img src="{{ $media->path }}" class="card-img" alt="{{ $property->search_headline }}">
                                    </a>
                                @endforeach
                            </div>
                        </div><!-- /.row -->
                    </div>
                @endforeach
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="property-controls">
                        <a class="properties-left u-inline-block u-mr05" href="#"></a>
                        <div class="property-dots u-inline-block u-mr05"></div>
                        <a class="properties-right u-inline-block" href="#"></a>
                    </div>
                </div>
            </div>
        </div><!-- /.container -->
    </section>
@endif
