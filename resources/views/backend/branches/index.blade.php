@extends('backend.layouts.master')

@section('admin-content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel pw">
                <div class="x_title">
                    <ul class="nav navbar-right panel_toolbox">
                        <li class="top-button"><a href="{{admin_url('branches/create')}}" class="btn btn-small btn-primary">Create Branch</a></li>
                    </ul>
                    <div class="clearfix"></div>
                    <div class="search-form-style-1">
                        <form
                            action="{{ action('Backend\MembersController@index') }}"
                            method="GET"
                            class="search-form"
                        >
                            <ul class="sf-field">

                                <li>
                                    <input
                                        type="text"
                                        name="q"
                                        class="form-control"
                                        placeholder="Search...."
                                        value="{{ request('q') }}"
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
                    </div>
                </div>
                <div class="x_content">
                    <div class="table-responsive pw-table">

                        @if(!empty($branches->count()))
                            <table class="table table-striped jambo_table bulk_action table-bordered-">
                                <thead>
                                <tr>
                                    <th>Branch Name</th>
                                    <th>Branch Address</th>
                                    <th>Branch Phone</th>
                                    <th>Branch Email</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($branches as $branch)
                                    <tr>
                                        <td>{{ $branch->branch_name }}</td>
                                        <td>
                                            {{ $branch->branch_address1 }}
                                            @if($branch->branch_address2)
                                                <br />
                                                {{ $branch->branch_address2 }}
                                                <br />
                                            @endif
                                            {{ $branch->branch_town }}
                                        </td>
                                        <td>{{ $branch->branch_phone }}</td>
                                        <td>{{ $branch->branch_email }}</td>
                                        <td align="center">
                                            <a href="{{admin_url('branches/'.$branch->branch_id.'/edit')}}" class="btn btn-small btn-primary">Edit</a>
                                            | <a href="#" class="btn btn-small btn-danger modal-toggle"
                                                 data-item-id="{{ $branch->branch_id }}"
                                                 data-toggle="modal"
                                                 data-modal-type="delete"
                                                 data-modal-title="Delete"
                                                 data-modal-size="small"
                                                 data-delete-type="branches"
                                                 data-target="#global-modal">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="no-data">
                                No branches found.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection