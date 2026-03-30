import $ from 'jquery';
window.$ = window.jQuery = $;

$(document).ready(function() {

    console.log("Aggregation search script loaded");



    let selectedType, selectedCountry, selectedArea, selectedComplex;


    // get area by location

    $('#location_list-').on('change', function() {

        console.log("Country select");

        selectedCountry = $(this).val();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/ajax/get-areas',
            type: 'POST',
            data: {
                country: selectedCountry
            },
            dataType: 'json',
            success: function(data) {
                let $dropdown = $('.select-pw-ajax-area');

                $dropdown.empty();

                console.log(data);

                $.each(data, function(index, area) {
                    $dropdown.append(
                        `<option value="${index}">${area}</option>`
                    );
                });
            },
            error: function(xhr) {
                console.error('AJAX Error:', xhr.responseText);
            }
        });

    });


    /* AREA -> PROJECT */
    $('#area_list-').on('change', function() {

        selectedArea = $(this).val();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/ajax/getprojects',
            type: 'POST',
            data: {
                area: selectedArea
            },
            dataType: 'json',
            success: function(data) {
                let $dropdown = $('.select-pw-ajax-complex');

                $dropdown.empty();

                console.log(data);

                $.each(data, function(index, state) {
                    $dropdown.append(
                        `<option value="${index}">${state}</option>`
                    );
                });
            },
            error: function(xhr) {
                console.error('AJAX Error:', xhr.responseText);
            }
        });

    });



    // function showSection(sectionId) {

    //     $('.grid-section').addClass('hidden');

    //     $(sectionId).removeClass('hidden');

    // }



    // $('#property-type-grid').on('click', '.btn', function() {

    //     console.log("Property type clicked");

    //     selectedType = $(this).data('type');

    //     console.log('Selected Type:', selectedType);

    //     loadCountries();

    // });



    // function loadCountries() {

    //     $.ajax({

    //         url: '/properties/get_countryList',

    //         method: 'POST',

    //         data: { q: '' },

    //         headers: {

    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

    //         },

    //         success: function(response) {

    //             console.log("Countries loaded:", response);

    //             let countries = JSON.parse(response);

    //             let html = countries.items.map(country => 

    //                 `<button class="btn" data-country="${country.country}">${country.country}</button>`

    //             ).join('');

    //             $('#country-grid').html(html);

    //             showSection('#country-grid');

    //         },

    //         error: function(xhr, status, error) {

    //             console.error("Error loading countries:", error);

    //         }

    //     });

    // }


    



    // $('#area-grid').on('click', '.btn', function() {

    //     console.log("Area clicked");

    //     selectedArea = $(this).data('area');

    //     console.log('Selected Area:', selectedArea);

    //     loadComplexes();

    // });



    // function loadComplexes() {

    //     $.ajax({

    //         url: '/properties/get_complexList',

    //         method: 'POST',

    //         data: { country: selectedCountry, area: selectedArea },

    //         headers: {

    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

    //         },

    //         success: function(response) {

    //             console.log("Complexes loaded:", response);

    //             let complexes = JSON.parse(response);

    //             let html = complexes.map(complex => 

    //                 `<button class="btn" data-complex="${complex.complex_name}">${complex.complex_name}</button>`

    //             ).join('');

    //             $('#complex-grid').html(html);

    //             showSection('#complex-grid');

    //         },

    //         error: function(xhr, status, error) {

    //             console.error("Error loading complexes:", error);

    //         }

    //     });

    // }



    // $('#complex-grid').on('click', '.btn', function() {

    //     console.log("Complex clicked");

    //     selectedComplex = $(this).data('complex');

    //     console.log('Selected Complex:', selectedComplex);

    //     performSearch();

    // });



    // function performSearch() {

    //     let url = `/${selectedType}-for-sale/in/${encodeURIComponent(selectedCountry)}/area/${encodeURIComponent(selectedArea)}/complex/${encodeURIComponent(selectedComplex)}`;

    //     console.log('Redirecting to:', url);

    //     window.location.href = url;

    // }



    // // Handle "More" dropdown

    // $('.dropdown-more').click(function() {

    //     $('.more').toggle();

    // });

});