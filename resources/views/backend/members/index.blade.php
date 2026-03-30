@extends('backend.layouts.master')

@section('admin-content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel pw">
                <div class="x_title">
                    <div class="clearfix"></div>
                    <div class="search-form-style-1">
                        <form
                            action="{{ action([App\Http\Controllers\Backend\MembersController::class, 'index']) }}"
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
                    </div>
                </div>
                <div class="x_content">
                    <div class="table-responsive pw-table">
                        {{$users->links('pagination::bootstrap-4')}}

                        @if(!empty($users->count()))
                            <table class="table table-striped jambo_table bulk_action table-bordered-">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Last Login</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if($user->last_login_at !== null)
                                                {{ Carbon\Carbon::parse($user->last_login_at)->diffForHumans()}}
                                            @else
                                                Never
                                            @endif
                                        </td>
                                        <td class="text-center table-active-btn">
                                            <a href="{{admin_url('members/'.$user->id.'')}}" class="btn btn-small btn-primary">View</a>
                                            |
                                            <a href="#" class="btn btn-small btn-danger modal-toggle" data-modal-title="Delete Member" data-item-id="{{ $user->id }}" data-toggle="modal" data-modal-type="delete" data-modal-size="small" data-delete-type="members" data-target="#global-modal">Delete</a>
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