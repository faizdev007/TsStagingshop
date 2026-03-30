

<select name="sort" class="select-pw">

    <option value="">{{ trans_fb('app.app_SortBy', 'SORT BY') }}</option>

    <option {{ set_selected('most-recent', post_criteria($criteria, 'sort')) }} value="{{ modify_url(['sort'=>'most-recent']) }}">Most Recent</option>

    <option {{ set_selected('lowest-price', post_criteria($criteria, 'sort')) }} value="{{ modify_url(['sort'=>'lowest-price']) }}">Lowest Price</option>

    <option {{ set_selected('highest-price', post_criteria($criteria, 'sort')) }} value="{{ modify_url(['sort'=>'highest-price']) }}">Highest Price</option>

    <option {{ set_selected('name', post_criteria($criteria, 'sort')) }} value="{{ modify_url(['sort'=>'name']) }}">Name A-Z</option>

</select>





<!-- <form action="{{ route('property.search') }}"
      method="POST"
      class="form form-1 property-search-form"
      id="property-search-form">

    @csrf

    <div class="search-form-home--container">
        <div class="search-form-home--wrap d-flex" data-aos="fade-righ">

            {{-- Location --}}
            <div class="search-form-home--item -country">
                <div id="location-buttons">
                    @foreach(get_locations() as $key => $location)
                        <button type="button"
                                class="btn btn-location"
                                data-value="{{ $key }}">
                            {{ $location }}
                        </button>
                    @endforeach
                </div>

                <input type="hidden"
                       name="in"
                       id="location_list"
                       value="{{ post_criteria($criteria, 'in') }}">
            </div>

            {{-- Area --}}
            <div class="search-form-home--item -country -area">
                <div id="area-buttons">
                    @foreach(get_areas('AREA') as $key => $area)
                        <button type="button"
                                class="btn btn-area"
                                data-value="{{ $key }}">
                            {{ $area }}
                        </button>
                    @endforeach
                </div>

                <input type="hidden"
                       name="area"
                       id="area_list"
                       value="{{ post_criteria($criteria, 'area') }}">
            </div>

            {{-- Complex --}}
            <div class="search-form-home--item -country -area">
                <div id="complex-buttons">
                    @foreach(get_complexx('COMPLEX') as $key => $complex)
                        <button type="button"
                                class="btn btn-complex"
                                data-value="{{ $key }}">
                            {{ $complex }}
                        </button>
                    @endforeach
                </div>

                <input type="hidden"
                       name="complex"
                       id="complex_list"
                       value="{{ post_criteria($criteria, 'complex') }}">
            </div>

            {{-- Property Type --}}
            <div class="search-form-home--item -type">
                <div id="type-buttons">
                    @foreach([
                        '' => trans_fb('app.app_PropertyType', 'TYPE'),
                        'apartment' => 'Apartment',
                        'penthouse' => 'Penthouse',
                        'Villa' => 'Villa',
                        'Townhouse' => 'Townhouse',
                        'Plot-Land' => 'Plot-Land',
                        'hotel-apartment' => 'Hotel apartment',
                        'building-bulk-deal' => 'Building or Bulk Deal'
                    ] as $key => $type)
                        <button type="button"
                                class="btn btn-property_type"
                                data-value="{{ $key }}">
                            {{ $type }}
                        </button>
                    @endforeach
                </div>

                <input type="hidden"
                       name="property_type"
                       id="property_type_list"
                       value="{{ post_criteria($criteria, 'property_type') }}">
            </div>

            {{-- Submit --}}
            <div class="search-form-home--item -search-btn">
                <input type="hidden" name="for" value="sale">

                <button type="submit"
                        name="button"
                        class="button -secondary u-height-100 u-width-full f-15 text-uppercase">
                    SEARCH
                </button>
            </div>

            <div class="u-clear"></div>
        </div>
    </div>

</form>







<script>

document.addEventListener('DOMContentLoaded', function() {

    document.querySelectorAll('.btn').forEach(button => {

        button.addEventListener('click', function() {

            let type = this.classList[1].split('-')[1]; // Get type from class name, e.g., 'property_type'

            document.querySelector(`#${type}_list`).value = this.dataset.value;



            // Remove active class from other buttons in the same category

            document.querySelectorAll(`.btn-${type}`).forEach(btn => btn.classList.remove('active'));

            // Add active class to the clicked button

            this.classList.add('active');

        });

    });

});





    </script>



<style>

.btn {

    padding: 10px 15px;

    margin: 5px;

    border: 1px solid #ccc;

    cursor: pointer;

    color:while;

    background-color:##d9b483;

    border-radius: 0px; 

}



.btn.active {

    

    

    border-color: #007bff;

    color:while;

    background-color:##d9b483;

    border-radius: 0px; 

}



.search-form-home--item {

    display: flex;

    flex-direction: column;

    margin-bottom: 15px;

}





    </style> -->





    <!-- <form action="{{ route('property.search') }}" method="POST" class="form form-1 property-search-form" id="property-search-form">

    <div class="search-form-home--container">

        <div class="search-form-home--wrap d-flex" data-aos="fade-right">

            <div class="search-form-home--item -country">

                <div id="location-buttons">

                    @foreach(get_locations() as $key => $location)

                        <button type="button" class="btn btn-location" data-value="{{ $key }}">{{ $location }}</button>

                    @endforeach

                </div>

                <input type="hidden" name="in" id="location_list" value="{{ post_criteria($criteria, 'in') }}">

            </div>

            <div class="search-form-home--item -country -area">

                <div id="area-buttons">

                    @foreach(get_areas('AREA') as $key => $area)

                        <button type="button" class="btn btn-area" data-value="{{ $key }}">{{ $area }}</button>

                    @endforeach

                </div>

                <input type="hidden" name="area" id="area_list" value="{{ post_criteria($criteria, 'area') }}">

            </div>

            <div class="search-form-home--item -country -area">

                <div id="complex-buttons">

                    @foreach(get_complexx('COMPLEX') as $key => $complex)

                        <button type="button" class="btn btn-complex" data-value="{{ $key }}">{{ $complex }}</button>

                    @endforeach

                </div>

                <input type="hidden" name="complex" id="complex_list" value="{{ post_criteria($criteria, 'complex') }}">

            </div>

            <div class="search-form-home--item -type">

                <div id="type-buttons">

                    @foreach([

                        '' => trans_fb('app.app_PropertyType', 'TYPE'), 

                        'apartment' => 'Apartment',

                        'penthouse' => 'Penthouse',

                        'Villa' => 'Villa',

                        'Townhouse' => 'Townhouse',

                        'Plot-Land' => 'Plot-Land',

                        'hotel-apartment' => 'Hotel apartment',

                        'building-bulk-deal' => 'Building or Bulk Deal'

                    ] as $key => $type)

                        <button type="button" class="btn btn-property_type" data-value="{{ $key }}">{{ $type }}</button>

                    @endforeach

                </div>

                <input type="hidden" name="property_type" id="property_type_list" value="{{ post_criteria($criteria, 'property_type') }}">

            </div>

            <div class="search-form-home--item -search-btn">

                <input type="hidden" name="for" value="sale">

                <button type="submit" name="button" class="button -secondary u-height-100 u-width-full f-15 text-uppercase">SEARCH</button>

            </div>

            <div class="u-clear"></div>

        </div>

    </div>

</form>



<style>

    .btn {

        padding: 10px 15px;

        margin: 5px;

        border: 1px solid #ccc;

        cursor: pointer;

        color: white;

        background-color: #d9b483;

        border-radius: 0px;

    }

    .btn.active {

        border-color: #007bff;

        color: white;

        background-color: #d9b483;

        border-radius: 0px;

    }

    .search-form-home--item {

        display: flex;

        flex-direction: column;

        margin-bottom: 15px;

    }

</style>



<script>

    document.addEventListener('DOMContentLoaded', function() {

        document.querySelectorAll('.btn').forEach(button => {

            button.addEventListener('click', function() {

                let type = this.classList[1].split('-')[1]; // Get type from class name, e.g., 'property_type'

                document.querySelector(`#${type}_list`).value = this.dataset.value;



                // Remove active class from other buttons in the same category

                document.querySelectorAll(`.btn-${type}`).forEach(btn => btn.classList.remove('active'));

                // Add active class to the clicked button

                this.classList.add('active');

            });

        });

    });

</script> -->





<!-- <form action="{{ route('property.search') }}" method="POST" class="form form-1 property-search-form" id="property-search-form">

    <div class="search-form-home--container">

        <div class="search-form-home--wrap d-flex flex-wrap" data-aos="fade-right">

            <div class="search-form-home--item -type">

                <div id="type-buttons">

                    @foreach([

                        '' => trans_fb('app.app_PropertyType', 'TYPE'), 

                        'apartment' => 'Apartment',

                        'penthouse' => 'Penthouse',

                        'Villa' => 'Villa',

                        'Townhouse' => 'Townhouse',

                        'Plot-Land' => 'Plot-Land',

                        'hotel-apartment' => 'Hotel apartment',

                        'building-bulk-deal' => 'Building or Bulk Deal'

                    ] as $key => $type)

                        <button type="button" class="btn btn-property_type" data-value="{{ $key }}">{{ $type }}</button>

                    @endforeach

                </div>

                <input type="hidden" name="property_type" id="property_type_list" value="{{ post_criteria($criteria, 'property_type') }}">

            </div>

            <div class="search-form-home--item -country">

                <div id="location-buttons">

                    @foreach(get_locations() as $key => $location)

                        <button type="button" class="btn btn-location" data-value="{{ $key }}">{{ $location }}</button>

                    @endforeach

                </div>

                <input type="hidden" name="in" id="location_list" value="{{ post_criteria($criteria, 'in') }}">

            </div>

        </div>

        <div class="search-form-home--wrap d-flex flex-wrap" data-aos="fade-right">

            <div class="search-form-home--item -country -area">

                <div id="area-buttons">

                    @foreach(get_areas('AREA') as $key => $area)

                        <button type="button" class="btn btn-area" data-value="{{ $key }}">{{ $area }}</button>

                    @endforeach

                </div>

                <input type="hidden" name="area" id="area_list" value="{{ post_criteria($criteria, 'area') }}">

            </div>

            <div class="search-form-home--item -country -area">

                <div id="complex-buttons">

                    @foreach(get_complexx('COMPLEX') as $key => $complex)

                        <button type="button" class="btn btn-complex" data-value="{{ $key }}">{{ $complex }}</button>

                    @endforeach

                </div>

                <input type="hidden" name="complex" id="complex_list" value="{{ post_criteria($criteria, 'complex') }}">

            </div>

            <div class="search-form-home--item -search-btn">

                <input type="hidden" name="for" value="sale">

                <button type="submit" name="button" class="button -secondary u-height-100 u-width-full f-15 text-uppercase">SEARCH</button>

            </div>

        </div>

    </div>

</form>



<style>

    .btn {

        padding: 10px 15px;

        margin: 5px;

        border: 1px solid #ccc;

        cursor: pointer;

        color: white;

        background-color: #d9b483;

        border-radius: 0px;

    }

    .btn.active {

        border-color: #007bff;

        color: white;

        background-color: #d9b483;

        border-radius: 0px;

    }

    .search-form-home--item {

        margin-right: 15px;

        margin-bottom: 15px;

    }

    .search-form-home--wrap {

        display: flex;

    }

</style>



<script>

    document.addEventListener('DOMContentLoaded', function() {

        document.querySelectorAll('.btn').forEach(button => {

            button.addEventListener('click', function() {

                let type = this.classList[1].split('-')[1]; // Get type from class name, e.g., 'property_type'

                document.querySelector(`#${type}_list`).value = this.dataset.value;



                // Remove active class from other buttons in the same category

                document.querySelectorAll(`.btn-${type}`).forEach(btn => btn.classList.remove('active'));

                // Add active class to the clicked button

                this.classList.add('active');

            });

        });

    });

</script> -->



<!-- <form action="{{ route('property.search') }}" method="POST" class="form form-1 property-search-form" id="property-search-form">

    <div class="search-form-home--container">

        <div class="search-form-home--wrap d-flex flex-wrap" data-aos="fade-right">

            <div class="search-form-home--item -type" id="type-section">

                <div id="type-buttons" aria-label="Property Type" role="group">

                    @foreach([

                        'apartment' => 'Apartment',

                        'penthouse' => 'Penthouse',

                        'Villa' => 'Villa',

                        'Townhouse' => 'Townhouse',

                        'Plot-Land' => 'Plot-Land',

                        'hotel-apartment' => 'Hotel apartment',

                        'building-bulk-deal' => 'Building or Bulk Deal'

                    ] as $key => $type)

                        <button type="button" class="btn btn-property_type" data-value="{{ $key }}" aria-controls="location-section">{{ $type }}</button>

                    @endforeach

                </div>

                <input type="hidden" name="property_type" id="property_type_list" value="{{ post_criteria($criteria, 'property_type') }}">

            </div>

            <div class="search-form-home--item -country" id="location-section" hidden>

                <div id="location-buttons" aria-label="Location" role="group">

                    @foreach(get_locations() as $key => $location)

                        <button type="button" class="btn btn-location" data-value="{{ $key }}" aria-controls="area-section">{{ $location }}</button>

                    @endforeach

                </div>

                <input type="hidden" name="in" id="location_list" value="{{ post_criteria($criteria, 'in') }}">

            </div>

        </div>

        <div class="search-form-home--wrap d-flex flex-wrap" data-aos="fade-right">

            <div class="search-form-home--item -country -area" id="area-section" hidden>

                <div id="area-buttons" aria-label="Area" role="group">

                    @foreach(get_areas('AREA') as $key => $area)

                        <button type="button" class="btn btn-area" data-value="{{ $key }}" aria-controls="complex-section">{{ $area }}</button>

                    @endforeach

                </div>

                <input type="hidden" name="area" id="area_list" value="{{ post_criteria($criteria, 'area') }}">

            </div>

            <div class="search-form-home--item -country -area" id="complex-section" hidden>

                <div id="complex-buttons" aria-label="Complex" role="group">

                    @foreach(get_complexx('COMPLEX') as $key => $complex)

                        <button type="button" class="btn btn-complex" data-value="{{ $key }}">{{ $complex }}</button>

                    @endforeach

                </div>

                <input type="hidden" name="complex" id="complex_list" value="{{ post_criteria($criteria, 'complex') }}">

            </div>

            <div class="search-form-home--item -search-btn">

                <input type="hidden" name="for" value="sale">

                <button type="submit" name="button" class="btn-search u-height-100 u-width-full f-15 text-uppercase">SEARCH</button>

            </div>

        </div>

    </div>

</form>



<style>

    .btn {

        padding: 10px 15px;

        margin: 5px;

        border: 1px solid #ccc;

        cursor: pointer;

        color: white;

        background-color: #d9b483;

        border-radius: 0px;

    }

    .btn.active {

        border-color: #007bff;

        color: white;

        background-color: #d9b483;

        border-radius: 0px;

    }

    .search-form-home--item {

        margin-right: 15px;

        margin-bottom: 15px;

    }

    .search-form-home--wrap {

        display: flex;

    }



    .btn-search {

        width: 150px; /* Smaller width */

        display: block; /* Center the button if needed */

        margin: 0 auto; /* Center the button horizontally */

        background-color: #d9b483;

        

    }

    

</style>



<script>

document.addEventListener('DOMContentLoaded', function() {

    document.querySelectorAll('.btn').forEach(button => {

        button.addEventListener('click', function() {

            let type = this.classList[1].split('-')[1]; // Get type from class name, e.g., 'property_type'

            document.querySelector(`#${type}_list`).value = this.dataset.value;



            // Remove active class from other buttons in the same category

            document.querySelectorAll(`.btn-${type}`).forEach(btn => btn.classList.remove('active'));

            // Add active class to the clicked button

            this.classList.add('active');



            // Hide current section and show the next one

            let nextSection = this.getAttribute('aria-controls');

            document.getElementById(nextSection).hidden = false;

            this.closest('.search-form-home--item').hidden = true;



            // Show search button only after the last selection

            if (nextSection === 'search-button') {

                document.getElementById('search-button').style.display = 'block';

            }

        });

    });

});

</script> -->























