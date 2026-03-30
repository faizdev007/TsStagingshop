@push('body_class') facebook-catalog-page @endpush

<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <h2>Export Properties to Facebook Catalog</h2>
            
            <!-- Quick Filter Options -->
            <!-- <div class="mb-4">
                <label>Quick Filter:</label>
                <select id="locationFilter" class="form-control-sm d-inline-block ml-2" style="width: 200px;">
                    <option value="">All Locations</option>
                    <option value="Palm Jumeirah">Palm Jumeirah</option>
                    <option value="Downtown">Downtown & Burj Khalifa</option>
                    <option value="Jumeirah Golf">Jumeirah Golf Estates</option>
                </select>
                <button type="button" onclick="selectByLocation()" class="btn btn-sm btn-primary ml-2">Select All</button>
                <button type="button" onclick="clearSelection()" class="btn btn-sm btn-secondary ml-2">Clear Selection</button>
            </div> -->

            <form action="{{ route('export.facebook.catalog') }}" method="POST" id="catalogForm">
                @csrf
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Ref</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Beds</th>
                                <th>Baths</th>
                                <th>Complex</th>
                                <th>Area</th>
                                <th>Country</th>
                                <th>Google Drive Image URL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($properties as $property)
                            <tr class="property-row" data-location="{{ $property->location }}">
                                <td>
                                    <input type="checkbox" name="properties[]" value="{{ $property->ref }}" class="property-checkbox">
                                </td>
                                <td>{{ $property->ref }}</td>
                                <td>{{ $property->name }}</td>
                                <td>{{ $property->price ? number_format($property->price, 0) . ' AED' : '-' }}</td>
                                <td>{{ $property->beds ?? '-' }}</td>
                                <td>{{ $property->baths ?? '-' }}</td>
                                <td>{{ $property->complex_name ?? '-' }}</td>
                                <td>{{ $property->town ?? '-' }}</td>
                                <td>{{ $property->country ?? '-' }}</td>
                                <td>
                                    <input type="text" name="image_urls[{{ $property->ref }}]" class="form-control form-control-sm" placeholder="Paste Google Drive URL">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">Generate Facebook Catalog XML</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function selectByLocation() {
    var location = document.getElementById('locationFilter').value;
    var rows = document.querySelectorAll('.property-row');
    
    rows.forEach(function(row) {
        var checkbox = row.querySelector('.property-checkbox');
        if (!location || row.dataset.location.includes(location)) {
            checkbox.checked = true;
            row.style.display = '';
        } else {
            checkbox.checked = false;
            row.style.display = 'none';
        }
    });
}

function clearSelection() {
    var checkboxes = document.querySelectorAll('.property-checkbox');
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = false;
    });
    
    var rows = document.querySelectorAll('.property-row');
    rows.forEach(function(row) {
        row.style.display = '';
    });
    
    document.getElementById('locationFilter').value = '';
}

// Form submission validation
document.getElementById('catalogForm').onsubmit = function(e) {
    var checked = document.querySelectorAll('.property-checkbox:checked').length > 0;
    if (!checked) {
        e.preventDefault();
        alert('Please select at least one property');
        return false;
    }
    return true;
};
</script>
@endpush