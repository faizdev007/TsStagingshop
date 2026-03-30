@push('body_class')generic-page @endpush
@extends('frontend.demo1.layouts.frontend')

@section('main_content')

<section class="page-title">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="text-center">
                    <h1 class="f-two f-38 f-bold c-white u-mb0 u-mt0">{{ trans_fb('app.app_Draw_Map', 'Drawmap') }}<span class="c-primary">.</span></h1>
                    <div class="generic-border u-center"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="drawmap-section">
    <div class="container">
        <div class="wraper">
            <div class="map-section">

                <div class="map-showcase drawmap-page">
                    <div class="row">
                        <div class="col-4">
                            <div class="back-cta"><a href="{{ url('property-for-sale') }}"><i class="fa fa-list" aria-hidden="true"></i> List View</a></div>
                        </div>
                        <div class="col-8">
                            <?php $mode = !empty($request->input('mode')) ? $request->input('mode') : 'sale'; ?>
                            @if(settings('sale_rent') == 'sale_rent')
                                <div id="drawmap-mode-switcher">
                                  <div class="draw-radio">
                                    <label class="container-checkbox">Buying
                                        <input type="radio" name="mode" class="mode" value="0"  {{ ($mode=='sale') ? 'checked':'' }} >
                                        <span class="checkmark"></span>
                                    </label>
                                  </div>
                                  <div class="draw-radio">
                                    <label class="container-checkbox">Rentals
                                        <input type="radio" name="mode" class="mode" value="1" {{ ($mode=='rent') ? 'checked':'' }} >
                                        <span class="checkmark"></span>
                                    </label>
                                  </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div id="drawmap">
                        <div id="drawmap-button-goto" href="javascript:void(0);">
                            <span id="drawmap-propcount"></span>
                        </div>
                        <div id="drawmap-map" data-lat="{{settings('default_latitude')}}" data-lng="{{settings('default_longitude')}}" data-zoom="14"></div>
                        <div id="drawmap-actions">
                            <div id="drawmap-button-clear" class="drawmap-button">Clear</div>
                            <div id="drawmap-button-help" class="drawmap-button">Help</div>
                        </div>
                        <div id="drawmap-help">
                            <div class="close-btn">
                                <a id="closeHelp" href="#"><i class="fa fa-times" aria-hidden="true"></i></a>
                            </div>
                            <h5>How to use this Drawmap</h5>
                            <ol>
                                <li>Click the map to drop your first area marker</li>
                                <li>Drop further markers to draw your search area</li>
                                <li>Double-click to close the search loop. Happy hunting.</li>
                            </ol>
                        </div>
                        <div id="drawmap-loading">Searching for properties &hellip;</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@push('frontend_scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key={{settings('google_map_api')}}&amp;libraries=drawing" defer></script>
@endpush
@push('frontend_footer-scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/OverlappingMarkerSpiderfier/1.0.3/oms.min.js" defer></script>
    <script src="{{themeAsset('vendor/drawmapjs/js/markerclusterer.js')}}" defer></script>
    <script src="{{themeAsset('vendor/drawmapjs/js/drawmap.js')}}" defer></script>
@endpush

@endsection
