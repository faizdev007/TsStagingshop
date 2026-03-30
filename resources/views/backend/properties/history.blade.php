@extends('backend.properties.template')



@section('property-content')

<div class="x_panel pw-inner-tabs">

    <div class="x_title">

        <h2>History of Changes for {{ $property->ref }}</h2>

        <div class="clearfix"></div>

    </div>

    <div class="x_content pw-open">

    <div class="print-header" style="display: none;">

    <div class="print-date">

        Print Date: {{ now()->format('d/m/Y') }}

    </div>

    <div class="text-center mb-4">

        <img src="{{ themeAsset('images/logos/logo.png') }}" width="200px" alt="Logo">

        <h2 class="mt-3">History of Changes for {{ $property->ref }}</h2>

    </div>

    <div class="divider"></div>

    <div class="text-left mb-4">

        <h2 class="mt-3">Title: {{ $property->name }}</h2>

    </div>

</div>

        <div class="xpw-fields">

        @php

    $logDirectory = storage_path('logs');

    $logPattern = 'property-updates*.log';

    $changes = collect();

    

    // Define field name mappings

    $fieldNames = [

        'internal_area' => '<strong>Area</strong>',

        'terrace_area' => '<strong>Terrace Size</strong>',

        'name' => '<strong>Name</strong>',

        'youtube_id' => '<strong>Youtube Link</strong>',

        'is_rental' => '<strong>Field Type</strong>',

        'user_id' => '<strong>Agent</strong>',

        'property_type_id' => '<strong>Property Type</strong>',

        'beds' => '<strong>Beds</strong>',

        'baths' => '<strong>Bath</strong>',

        'price' => '<strong>Price</strong>',

        'add_info' => '<strong>Additional Info</strong>',

        'description' => '<strong>Description</strong>',

        'city' => '<strong>City</strong>',

        'status' => '<strong>Status</strong>',

        'Additional Info' => '<strong>Key Features</strong>',

        'complex_name' => '<strong>Complex Name</strong>',

    ];

    

    // Get status names mapping

    $statusNames = p_states();

    

    // Get all property update log files

    $logFiles = glob($logDirectory . '/' . $logPattern);

    

    foreach ($logFiles as $logPath) {

        if (file_exists($logPath)) {

            $lines = array_filter(

                file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES),

                function($line) use ($property) {

                    return stripos($line, "Property {$property->ref}") !== false;

                }

            );

            

            foreach($lines as $line) {

                if (preg_match('/^\[(.+?)\].+?: (.+)$/', $line, $matches)) {

                    $content = $matches[2];

                    $jsonStart = strrpos($content, '{');

                    if ($jsonStart !== false) {

                        $message = trim(substr($content, 0, $jsonStart));

                        preg_match('/updated by ([^\s{]+)/', $message, $userMatch);

                        $userName = $userMatch[1] ?? 'System';

                        

                        $jsonData = json_decode(substr($content, $jsonStart), true);

                        if (isset($jsonData['changes'])) {

                            // Replace field names in the changes string

                            $changes_text = $jsonData['changes'];

                            

                            // Replace status numbers with names

                            if (strpos($changes_text, 'status:') !== false || strpos($changes_text, 'Status:') !== false) {

                                preg_match('/(\d+)\s*→\s*(\d+)/', $changes_text, $statusMatches);

                                if (!empty($statusMatches)) {

                                    $oldStatus = $statusNames[$statusMatches[1]] ?? $statusMatches[1];

                                    $newStatus = $statusNames[$statusMatches[2]] ?? $statusMatches[2];

                                    $changes_text = preg_replace(

                                        '/(\d+)\s*→\s*(\d+)/',

                                        $oldStatus . ' → ' . $newStatus,

                                        $changes_text

                                    );

                                }

                            }

                            

                            // Replace other field names

                            foreach ($fieldNames as $original => $display) {

                                $changes_text = str_replace($original, $display, $changes_text);

                            }

                            

                            $changes->push([

                                'timestamp' => \Carbon\Carbon::parse($matches[1])->timestamp,

                                'date' => \Carbon\Carbon::parse($matches[1])->format('F jS Y'),

                                'user' => $userName,

                                'changes' => $changes_text

                            ]);

                        }

                    }

                }

            }

        }

    }

                

                // Apply filters

                if (request('from_date')) {

                    $changes = $changes->filter(function($change) {

                        return Carbon\Carbon::parse($change['date'])->gte(Carbon\Carbon::parse(request('from_date')));

                    });

                }

                

                if (request('to_date')) {

                    $changes = $changes->filter(function($change) {

                        return Carbon\Carbon::parse($change['date'])->lte(Carbon\Carbon::parse(request('to_date')));

                    });

                }

                

                if (request('user')) {

                $changes = $changes->filter(function($change) {

                 return strcasecmp($change['user'], request('user')) === 0;

                });

                }

                

                if (request('field')) {

    $changes = $changes->filter(function($change) use ($fieldNames) {

        $searchField = request('field');

        $displayName = strip_tags($fieldNames[$searchField] ?? '');

        

        // Check for both the original field name and the display name

        return strpos($change['changes'], $displayName) !== false;

    });

}

                

                // Sort by timestamp to ensure correct date ordering

                $changes = $changes->sortByDesc('timestamp');

                

                // Get unique users for filter dropdown

                $users = $changes->pluck('user')->unique()->sort()->mapWithKeys(function($user) {

                 return [$user => $user];

                })->all();

            @endphp



            <!-- Filter Section -->

            <form method="GET" class="search-form">

    <ul class="sf-field">

        <li class="small">
            <input
                type="text"
                name="from_date"
                value="{{ request('from_date') }}"
                class="form-control datepicker"
                placeholder="Date From"
                autocomplete="off"
            >
        </li>

        <li class="small">
            <input
                type="text"
                name="to_date"
                value="{{ request('to_date') }}"
                class="form-control datepicker"
                placeholder="Date To"
                autocomplete="off"
            >
        </li>

        <li class="medium">
            <select name="user" class="form-control select-pw">
                <option value="">All Users</option>
                @foreach($users as $key => $label)
                    <option
                        value="{{ $key }}"
                        {{ request('user') == $key ? 'selected' : '' }}
                    >
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </li>

        <li class="large">
            <select name="field" class="form-control select-pw">
                <option value="">All Fields</option>
                @foreach($fieldNames as $key => $label)
                    <option
                        value="{{ $key }}"
                        {{ request('field') == $key ? 'selected' : '' }}
                    >
                        {{ strip_tags($label) }}
                    </option>
                @endforeach
            </select>
        </li>

        <li>
            <div class="pws-search-btn">

                <div class="psb-col">
                    <button
                        type="submit"
                        class="btn btn-small btn-primary pw-search-btn"
                    >
                        Search
                    </button>
                </div>

                <div class="psb-col">
                    <a
                        href="{{ request()->url() }}"
                        class="btn btn-small btn-default pw-search-btn"
                    >
                        Reset
                    </a>
                </div>

                <div class="psb-col">
                    <button
                        type="button"
                        onclick="window.print()"
                        class="btn btn-small btn-primary"
                    >
                        <i class="fa fa-print"></i> Print Report
                    </button>
                </div>

            </div>
        </li>

    </ul>

</form>



            <div class="row">

                <div class="col-md-12">

                    <div class="property-meta">

                        <small>

                            <i class="fa fa-calendar-plus-o"></i> 

                            <span class="text-muted">Listing Created:</span> 

                            <span class="text-dark">{{ \Carbon\Carbon::parse($property->display_date)->format('F jS Y') }}</span>

                        </small>

                    </div>



                    <div class="table-responsive mt-3">

                        <table class="table table-striped table-bordered">

                            <thead>

                                <tr>

                                    <th width="20%">Updated Date</th>

                                    <th width="15%">By User</th>

                                    <th>Changes (Before -> After)</th>

                                </tr>

                            </thead>

                            <tbody>

                            @forelse($changes as $change)

                                <tr>

                                    <td>{{ $change['date'] }}</td>

                                    <td>{{ $change['user'] }}</td>

                                    <td>{!! $change['changes'] !!}</td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="3" class="text-center text-muted py-3">

                                        <em>No changes have been recorded for this property</em>

                                    </td>

                                </tr>

                            @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>



<style>

.property-meta {

    padding: 6px 0;

    border-bottom: 1px solid #e9ecef;

}

.property-meta small {

    font-size: 12px;

    line-height: 1.4;

}

.property-meta i {

    margin-right: 4px;

    color: #6c757d;

}

.table td {

    vertical-align: middle;

}

.sf-field {

    list-style: none;

    padding: 0;

    margin: 0 0 20px;

    display: flex;

    flex-wrap: wrap;

    align-items: flex-end;

    gap: 10px;

}

.sf-field li {

    margin: 0;

}

.sf-field li.small {

    width: 150px;

}

.sf-field li.medium {

    width: 200px;

}

.sf-field li.large {

    width: 250px;

}

.pws-search-btn {

    display: flex;

    gap: 10px;

}

.psb-col {

    display: inline-block;

}

.select-pw {

    width: 100%;

}

.datepicker {

    background-color: #fff;

}

.btn-small {

    padding: 5px 10px;

    font-size: 12px;

}

@media print {

    @page {

        margin: 5px;

        size: A4;

    }

    

    body * {

        visibility: hidden;

    }



    .divider {

        height: 5px;

        background-color: #ff0000; /* Changed to red */

        margin: 20px 0;

        visibility: visible;

    }

    

    .print-header {

        display: block !important;

        visibility: visible;

        margin: 0 0 20px 0;

        position: fixed;

        top: 20px; /* Added top padding */

        left: 5px;

        right: 5px;

        text-align: center;

    }



    .print-date {

        position: absolute;

        left: 5px;

        top: 5px;

        font-size: 12px;

        color: #666;

    }

    

    .print-header h2 {

        font-weight: 500; /* Medium font weight */

        font-size: 25px;

        margin-top: 30px;

       font-family: 'Cormorant, sans-serif';

       padding-top: 20px;

    }



    /* Add specific style for the title */

    .text-left h2 {

        font-weight: 500;

        font-size: 20px;

        margin-top: 15px;

        font-family: 'Cormorant, sans-serif';

        padding-left: 20px;

    }

    

    .print-header * {

        visibility: visible;

    }



     /* Add page number to footer */

     .table-responsive {

        position: relative;

    }

    

    .x_panel.pw-inner-tabs, .x_panel.pw-inner-tabs * {

        visibility: visible;

    }

    

    .x_panel.pw-inner-tabs {

        position: absolute;

        left: 5px;

        top: 20px; /* Added top padding */

        right: 5px;

        padding: 0;

        margin: 0;

    }

    

    .x_title, .pws-search-btn, .sf-field {

        display: none !important;

    }

    

    .table {

        width: 100%;

        border-collapse: collapse;

        margin-top: 10px;

    }

    

    .table th, .table td {

        border: 1px solid #ddd;

        padding: 8px;

    }

    

    .property-meta {

        margin-top: 10px;

        font-size: 14px;

    }

    

    .xpw-fields {

        padding: 0;

        margin: 0;

    }

    

    /* Reset any default margins */

    .x_content {

        padding: 0 !important;

        margin: 0 !important;

    }

}





</style>



@push('scripts')

<script>

$(document).ready(function() {

    $('.datepicker').datepicker({

        format: 'yyyy-mm-dd',

        autoclose: true,

        todayHighlight: true

    });

});

</script>

<script>

document.addEventListener('DOMContentLoaded', function() {

    window.printReport = function() {

        window.print();

    }

});

</script>

@endpush

@endsection