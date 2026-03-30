@extends('backend.layouts.master')

@section('admin-content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel pw">
                <div class="x_title">
                    <div class="search-form-style-1">
                        <form action="{{ route('search-content.index') }}"
                            method="GET"
                            class="search-form">

                            <ul class="nav navbar-right panel_toolbox">
                                <li class="top-button">
                                    <a href="{{ admin_url('search-content/create') }}"
                                    class="btn btn-small btn-primary">
                                        Create New
                                    </a>
                                </li>
                            </ul>

                            <ul class="sf-field">

                                {{-- Keyword --}}
                                <li>
                                    <input type="text"
                                        name="q"
                                        class="form-control"
                                        placeholder="Keyword..."
                                        value="{{ request('q') }}">
                                </li>

                                {{-- Actions --}}
                                <li>
                                    <div class="pw-search-btn">
                                        <div class="psb-col">
                                            <button type="submit"
                                                    name="search"
                                                    value="yes"
                                                    class="btn btn-small btn-primary pw-search-btn">
                                                Search
                                            </button>
                                        </div>

                                        <div class="psb-col">
                                            <a href="{{ url()->current() }}"
                                            class="btn btn-small btn-default pw-search-btn">
                                                Clear <span>Search</span>
                                            </a>
                                        </div>
                                    </div>
                                </li>

                            </ul>

                        </form>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="table-responsive pw-table">
                        {{$content->links('pagination::bootstrap-4')}}

                        @if(!empty($content->count()))
                            <table class="table table-striped jambo_table bulk_action table-bordered-">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>URL</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($content as $content)
                                    <tr>
                                        <td>{{ $content->content_title }}</td>
                                        <td>{{ $content->content_url }}</td>
                                        <td class="text-center table-active-btn">
                                            <a href="{{admin_url('search-content/'.$content->id.'/edit')}}" class="btn btn-small btn-primary">Edit</a>
                                            | <a href="#" class="btn btn-small btn-danger modal-toggle"
                                                 data-item-id="{{ $content->id }}"
                                                 data-toggle="modal"
                                                 data-modal-type="delete"
                                                 data-modal-title="Delete"
                                                 data-modal-size="small"
                                                 data-delete-type="search-content"
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