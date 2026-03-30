@extends('backend.layouts.master')

@section('admin-content')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel pw">
                <div class="x_title">
                    <h2>Client Search</h2>
                    <div class="search-form-style-1">
                        <form
                            action="{{ action([App\Http\Controllers\Backend\ClientValuationsController::class, 'index']) }}"
                            method="GET"
                            class="search-form"
                        >
                            <ul class="sf-field">
                                <li>
                                    <input
                                        type="text"
                                        name="q"
                                        class="form-control"
                                        placeholder="Keyword..."
                                        value="{{ request()->get('q') }}"
                                    >
                                </li>

                                <li>
                                    <div class="pw-search-btn">
                                        <div class="psb-col">
                                            <button
                                                type="submit"
                                                name="search"
                                                value="yes"
                                                class="btn btn-small btn-primary pw-search-btn"
                                            >
                                                Search
                                            </button>
                                        </div>

                                        <div class="psb-col">
                                            <a
                                                href="{{ url()->current() }}"
                                                class="btn btn-small btn-default pw-search-btn"
                                            >
                                                Clear <span>Search</span>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </form>
                        <ul class="nav navbar-right panel_toolbox">
                            <li class="top-button"><a href="{{admin_url('market-valuation/create')}}" class="btn btn-small btn-primary">Create New</a></li>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="table-responsive pw-table">
                        {{ $valuations->links('pagination::bootstrap-4') }}

                        @if(!empty($valuations->count()))
                            <table class="table table-striped jambo_table bulk_action table-bordered-">
                                <thead>
                                <tr>
                                    <th>Client Name</th>
                                    <th>Property Address</th>
                                    <th>Price</th>
                                    <th>Sent Date</th>
                                    <th>Status</th>
                                    <th>Date Of Instruction</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($valuations as $valuation)
                                    <tr>
                                        <td>
                                            @if($valuation->client->count() > 0)
                                                {{ $valuation->client->client_name }}
                                            @endif
                                        </td>
                                        <td>
                                            {{ $valuation->client_valuation_street }}, {{ $valuation->client_valuation_city }}, {{ $valuation->client_valuation_postcode }}
                                        </td>
                                        <td>
                                            <?php $price_value = sprintf('%s%s', settings('currency_symbol'), number_format($valuation->client_valuation_price)); echo $price_value; ?>
                                        </td>
                                        <td>
                                            {{ date("jS F Y", strtotime($valuation->created_at)) }} at {{ date("g:ia", strtotime($valuation->created_at)) }}
                                        </td>
                                        <td>
                                            {{ ucfirst($valuation->client_valuation_status) }}
                                        </td>
                                        <td>
                                            @if($valuation->client_valuation_status == 'instructed')
                                                {{ date("jS F Y", strtotime($valuation->client_valuation_instructed_date )) }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{admin_url('market-valuation/'.$valuation->client_valuation_id.'/edit')}}" class="btn btn-small btn-primary">Edit</a> |
                                            <a target="_blank" href="{{ url('valuation-report/'.$valuation->uuid) }}" class="btn btn-small btn-info">View</a> |
                                            <a href="#" class="btn btn-small btn-danger modal-toggle"
                                               data-item-id="{{ $valuation->client_valuation_id }}"
                                               data-toggle="modal"
                                               data-modal-type="delete"
                                               data-modal-title="Delete"
                                               data-modal-size="small"
                                               data-delete-type="market-valuation"
                                               data-target="#global-modal">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="no-data">
                                No data found.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('headerscripts')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endpush

@push('footerscripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{url('assets/admin/build/js/pw-testimonials.js')}}"></script>
@endpush
