<!-- Grid Style Filters -->
<div class="filter-grid">
    <div class="container">
        <div class="row">
            <!-- Property Type Filter -->
            <div class="col-md-3 mb-3">
                <div class="filter-card" data-bs-toggle="modal" data-bs-target="#propertyTypeModal">
                    <h4 class="filter-title">Property Type</h4>
                    <div class="selected-options">
                        @php
                            $selectedTypes = post_criteria($criteria, 'property-type');
                            $selectedTypeArray = $selectedTypes ? explode(',', $selectedTypes) : [];
                            $selectedCount = count($selectedTypeArray);
                        @endphp
                        <span class="selected-text">{{ $selectedCount ? "$selectedCount selected" : 'All Types' }}</span>
                        <span class="total-count">{{ $propertyTypes->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Location Filter -->
            <div class="col-md-3 mb-3">
                <div class="filter-card" data-bs-toggle="modal" data-bs-target="#locationModal">
                    <h4 class="filter-title">Location</h4>
                    <div class="selected-options">
                        @php
                            $selectedLocation = post_criteria($criteria, 'in');
                        @endphp
                        <span class="selected-text">{{ $selectedLocation ?: 'All Locations' }}</span>
                        <span class="total-count">{{ $locations->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Area Filter -->
            <div class="col-md-3 mb-3">
                <div class="filter-card" data-bs-toggle="modal" data-bs-target="#areaModal">
                    <h4 class="filter-title">Area</h4>
                    <div class="selected-options">
                        @php
                            $selectedArea = post_criteria($criteria, 'area');
                        @endphp
                        <span class="selected-text">{{ $selectedArea ?: 'All Areas' }}</span>
                        <span class="total-count">{{ $areas->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Complex Filter -->
            <div class="col-md-3 mb-3">
                <div class="filter-card" data-bs-toggle="modal" data-bs-target="#complexModal">
                    <h4 class="filter-title">Complex</h4>
                    <div class="selected-options">
                        @php
                            $selectedComplex = post_criteria($criteria, 'complex');
                        @endphp
                        <span class="selected-text">{{ $selectedComplex ?: 'All Complexes' }}</span>
                        <span class="total-count">{{ $complexes->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Property Type Modal -->
<div class="modal fade filter-modal" id="propertyTypeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Property Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="filter-options">
                    @foreach($propertyTypes as $type)
                        <div class="filter-option">
                            <input type="checkbox" 
                                   class="instant-filter"
                                   id="type-{{ $type->id }}"
                                   name="property-type[]" 
                                   value="{{ $type->slug }}-for-sale"
                                   {{ in_array($type->slug . '-for-sale', $selectedTypeArray) ? 'checked' : '' }}>
                            <label for="type-{{ $type->id }}">
                                {{ $type->name }}
                                <span class="count">({{ $type->properties_count }})</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Location Modal -->
<div class="modal fade filter-modal" id="locationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Location</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="filter-options">
                    @foreach($locations as $location)
                        <div class="filter-option">
                            <input type="radio" 
                                   class="instant-filter"
                                   id="location-{{ $loop->index }}"
                                   name="in" 
                                   value="{{ $location->country }}"
                                   {{ post_criteria($criteria, 'in') == $location->country ? 'checked' : '' }}>
                            <label for="location-{{ $loop->index }}">
                                {{ $location->country }}
                                <span class="count">({{ $location->properties_count }})</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Area Modal -->
<div class="modal fade filter-modal" id="areaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Area</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="filter-options">
                    @foreach($areas as $area)
                        <div class="filter-option">
                            <input type="radio" 
                                   class="instant-filter"
                                   id="area-{{ $loop->index }}"
                                   name="area" 
                                   value="{{ $area->area }}"
                                   {{ post_criteria($criteria, 'area') == $area->area ? 'checked' : '' }}>
                            <label for="area-{{ $loop->index }}">
                                {{ $area->area }}
                                <span class="count">({{ $area->properties_count }})</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Complex Modal -->
<div class="modal fade filter-modal" id="complexModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Complex</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="filter-options">
                    @foreach($complexes as $complex)
                        <div class="filter-option">
                            <input type="radio" 
                                   class="instant-filter"
                                   id="complex-{{ $loop->index }}"
                                   name="complex" 
                                   value="{{ $complex->complex_name }}"
                                   {{ post_criteria($criteria, 'complex') == $complex->complex_name ? 'checked' : '' }}>
                            <label for="complex-{{ $loop->index }}">
                                {{ $complex->complex_name }}
                                <span class="count">({{ $complex->properties_count }})</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('frontend_js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle instant filtering
    document.querySelectorAll('.instant-filter').forEach(function(input) {
        input.addEventListener('change', function() {
            // Get the current URL
            let url = new URL(window.location.href);
            let params = new URLSearchParams(url.search);
            
            if (this.type === 'checkbox') {
                // Handle multiple selections for checkboxes
                let values = [];
                document.querySelectorAll(`input[name="${this.name}"]:checked`).forEach(checkbox => {
                    values.push(checkbox.value);
                });
                
                if (values.length > 0) {
                    params.set(this.name.replace('[]', ''), values.join(','));
                } else {
                    params.delete(this.name.replace('[]', ''));
                }
            } else {
                // Handle single selection for radio buttons
                if (this.checked) {
                    params.set(this.name, this.value);
                } else {
                    params.delete(this.name);
                }
            }
            
            // Update URL and reload page
            url.search = params.toString();
            window.location.href = url.toString();
        });
    });
});
</script>
@endpush