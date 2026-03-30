@extends('backend.layouts.master')



@section('admin-content')



<style>
    .search-form-container {

        display: flex;

        overflow-x: auto;

        white-space: nowrap;

        align-items: center;

        padding: 10px;

    }



    /* Adjust spacing between elements */

    .search-form-container label,

    .search-form-container input,

    .search-form-container button {

        margin-right: 10px;

    }



    /* Optional: Set a maximum width for the container to limit horizontal scrolling */

    /* .search-form-container {

    max-width: 100%;

} */



    /* Responsive layout for smaller screens (e.g., mobile devices) */

    @media (max-width: 768px) {

        .search-form-container {

            flex-direction: column;
            /* Stack elements vertically */

            align-items: flex-start;
            /* Align elements to the start (left) */

        }



        /* Reset white-space property for mobile */

        .search-form-container {

            white-space: normal;

        }



        /* Adjust spacing between elements for mobile */

        .search-form-container label,

        .search-form-container input,

        .search-form-container button {

            margin-right: 0;
            /* Reset margin for mobile */

            margin-bottom: 10px;
            /* Add vertical spacing for mobile */

        }



        /* Optional: Set a maximum width for the container on mobile */

        /* .search-form-container {

        max-width: 100%;

    } */

    }
</style>



<div class="row">

    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="x_panel pw">



            <div class="x_title">



                <div class="search-form-container">

                    <div>
                        <label for="searchRef" style="display:none;">Search by Ref:</label>

                        <input type="text" id="searchRef" class="medium form-control" placeholder="Search by Ref:">
                    </div>
                    <div>
                        <label for="startDate" style="display:none;">Start Date:</label>

                        <input type="text" id="startDate" class="datepicker form-control" placeholder="Start Date:">
                    </div>
                    <div>
                        <label for="endDate" style="display:none;">End Date:</label>

                        <input type="text" id="endDate" class="datepicker form-control" placeholder="End Date:">
                    </div>
                    <button id="filterButton" class="btn btn-primary" style="margin: 0; border-radius: 0px; padding: 0.8rem;">Search</button>

                    <button id="resetButton" class="btn btn-secondary" style="margin: 0; border-radius: 0px; padding: 0.8rem;">Clear Search</button>
                </div>

            </div>





            <div class="x_content">

                <div class="table-responsive pw-table">



                    <div class="scroll">

                        <table class="table table-striped jambo_table bulk_action table-bordered-">

                            <thead>

                                <tr>



                                    <th>Serial Number</th>

                                    <th>Ref</th>

                                    <th>Title</th>

                                    <th>Last Click Date</th>

                                    <th>Total Clicks</th>



                                </tr>

                            </thead>

                            <tbody>

                                @foreach($datas as $index => $row)

                                <tr class="data-row">



                                    <td>{{ $index + 1 }}</td>

                                    <td>{{ $row->ref }}</td>

                                    <td>{{ $row->p_name }}</td>

                                    <td>{{ \Carbon\Carbon::parse($row->latest_clicked_at)->format('d-M-Y') }}</td>

                                    <td>{{ $row->ref_count }}</td>



                                </tr>

                                @endforeach

                            </tbody>

                        </table>


                        <div style="margin-top: 15px;">
                            {{ $datas->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

</div>





@endsection



@push('headerscripts')

<meta name="csrf-token" content="{{ csrf_token() }}">

@endpush



@push('footerscripts')

<script src="{{asset('assets/admin/build/vendors/jquery/jquery.jscroll.min.js')}}"></script>

<script src="{{asset('assets/admin/build/js/pw-lazy-pagination.js')}}"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">







<!-- This code is for filter the ref number -->

<script>
    $(document).ready(function() {

        $('#filterButton').click(function() {

            var searchValue = $('#searchRef').val().trim().toLowerCase();



            // Hide all rows initially

            $('.data-row').hide();



            // Show rows that match the search value

            $('.data-row').filter(function() {

                var refValue = $(this).find('td:eq(1)').text().trim().toLowerCase();

                return refValue.includes(searchValue);

            }).show();

        });

    });



    $(document).ready(function() {

        // Initialize datepicker for the input fields

        $(".datepicker").datepicker({

            dateFormat: "dd-M-yy", // Date format (e.g., 26-Sep-2023)

        });



        $('#filterButton').click(function() {

            filterData();

        });



        function filterData() {

            var startDate = $('#startDate').val();

            var endDate = $('#endDate').val();



            // Convert the selected dates to timestamps for comparison

            var startTimestamp = $.datepicker.parseDate("dd-M-yy", startDate).getTime();

            var endTimestamp = $.datepicker.parseDate("dd-M-yy", endDate).getTime();



            // Hide all rows initially

            $('.data-row').hide();



            // Show rows within the selected date range

            $('.data-row').filter(function() {

                var rowDate = $.datepicker.parseDate("dd-M-yy", $(this).find('td:eq(3)').text()).getTime();

                return rowDate >= startTimestamp && rowDate <= endTimestamp;

            }).show();

        }

    });
</script>

<!-- This code is for filter the ref number -->



<!-- --------------------------------------------------------------------------------------------- -->



<!-- This code is for Reset the search  -->

<script>
    $(document).ready(function() {

        $('#filterButton').click(function() {

            // Filter logic here...

        });



        $('#resetButton').click(function() {

            // Clear search input and date inputs

            $('#startDate').val('');

            $('#endDate').val('');

            $('#searchRef').val('');



            // Show all rows

            $('.data-row').show();

        });

    });
</script>

<!-- This code is for Reset the search  -->
@endpush