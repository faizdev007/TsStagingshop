{{-- SWITCHING MAPS FROM SETTINGS --}}
@php
$google_map_api = settings('google_map_api');
@endphp

@if($google_map_api)
    @push('frontend_scripts')
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;key={{settings('google_map_api') }}" defer></script>
    @endpush
    @push('frontend_foote-scripts')
        <script src="https://unpkg.com/codezero-mapify@1.0.1/dist/mapify.min.js" async defer></script>     
        <script src="{{ asset('assets/shared/js/google-map.js')}}" async></script>
    @endpush
@else
    @push('frontend_css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.4/leaflet.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lvoogdt/Leaflet.awesome-markers@2.0.2/dist/leaflet.awesome-markers.css"/>
    @endpush

    @push('frontend_footer-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.4/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/lvoogdt/Leaflet.awesome-markers@2.0.2/dist/leaflet.awesome-markers.js"></script>
    <script src="{{ asset('assets/shared/js/leaflet-map.js')}}"></script>
    @endpush
@endif
