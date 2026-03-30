@push('body_class') search-page-2 shortlist-page @endpush

<section class="page-title -account-area {{(count($properties))?'u-mb1':''}} ">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="text-center">
                    <h1 class="f-four f-38 f-500 c-white u-mb0 u-mt0">
                        @if(count($properties))
                            Your Property Shortlist
                        @else
                            You have no properties in your shortlist
                        @endif
                    </h1>
                </div>
            </div>
        </div>
    </div>
</section>

@if(count($properties))
<section class="properties-section">
    <div class="container">
        <div class="-search-action">
            <div class="pagination-style-1 u-text-right">
                {{ $properties->onEachSide(1)->links('vendor.pagination.style-1') }}
            </div>
        </div>
    </div>
    <div class="container-style-1">
        <div class="-wrap">
            <div class="row">
                @foreach ($properties as $property)
                <div class="col-md-4 col-sm-6">
                    @include('frontend.demo1.parts.property-grid')
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="container">
        <div class="-search-action u-mt05">
            <div class="pagination-style-1 -no-margin u-text-right">
                {{ $properties->onEachSide(1)->links('vendor.pagination.style-1') }}
            </div>
        </div>
    </div>
</section>
@endif
