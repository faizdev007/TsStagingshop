@extends('frontend.demo1.layouts.app')

@section('content')
<div class="container-fluid py-5">
    <div class="row">
        <!-- Grid Filters -->
        <div class="col-md-12 mb-4">
            @include('frontend.demo1.partials.grid-filters')
        </div>

        <!-- Property Grid -->
        <div class="col-md-12">
            <div class="row">
                @forelse($properties as $property)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            @if($property->featured_image)
                                <img src="{{ $property->featured_image }}" class="card-img-top" alt="{{ $property->title }}">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $property->title }}</h5>
                                <p class="card-text">
                                    <strong>Location:</strong> {{ $property->country }}<br>
                                    <strong>Area:</strong> {{ $property->area }}<br>
                                    <strong>Complex:</strong> {{ $property->complex }}<br>
                                    <strong>Price:</strong> {{ number_format($property->price) }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">
                            No properties found matching your criteria.
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="row mt-4">
                <div class="col-12">
                    {{ $properties->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')
<script>
    // Update URL parameters when filters change
    function updateUrlParams(key, value) {
        let url = new URL(window.location.href);
        if (value) {
            url.searchParams.set(key, value);
        } else {
            url.searchParams.delete(key);
        }
        window.history.pushState({}, '', url);
        window.location.reload();
    }
</script>
@endpush
@endsection
