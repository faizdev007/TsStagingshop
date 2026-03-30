@extends('backend.layouts.master')

@section('admin-content')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel pw">
                <div class="x_title">
                    <div class="search-form-style-1">
                        <form
                            action="{{ action('Backend\BespokePagesController@index') }}"
                            method="GET"
                            class="search-form"
                        >
                            <ul class="sf-field">

                                <li>
                                    <input
                                        type="text"
                                        name="q"
                                        value="{{ $request->input('q') }}"
                                        class="form-control"
                                        placeholder="Keyword..."
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
                        @if(  Auth::user()->role_id == '1' )
                            <ul class="nav navbar-right panel_toolbox">
                                <li class="top-button"><a href="{{admin_url('pages/create')}}" class="btn btn-small btn-primary">Create New</a></li>
                            </ul>
                        @endif
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="table-responsive pw-table">
                       
                        {{ $pages->links('pagination::bootstrap-4') }}

                        @if(!empty($pages->count()))
                            <table class="table table-striped jambo_table bulk_action table-bordered-">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>URL</th>
                                    <th>Content</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($pages as $page)
                                    <tr id="item-{{$page->id}}">
                                        <td>{{strip_tags($page->title)}}</td>
                                        <td>{{strip_tags($page->route)}}</td>
                                        <td>{{Str::limit(strip_tags($page->content), 70)}}</td>
                                        <td class="text-center table-active-btn">
                                            <a href="{{admin_url('bespoke-pages/'.$page->id.'/edit')}}" class="btn btn-small btn-primary">Edit</a> |
                                            <a href="{{url($page->route)}}" class="btn btn-small btn-info" target="_blank">View</a> |
                                            <a href="#" class="btn btn-small btn-danger modal-toggle"
                                               data-item-id="{{ $page->id }}"
                                               data-toggle="modal"
                                               data-modal-type="delete"
                                               data-modal-title="Delete"
                                               data-modal-size="small"
                                               data-delete-type="pages"
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
