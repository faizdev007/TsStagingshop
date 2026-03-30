@extends('backend.layouts.master')

@section('admin-content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel pw">
                <div class="x_title">
                    <div class="search-form-style-1">
                        <form
                            action="{{ action([App\Http\Controllers\Backend\FooterBlockController::class, 'index']) }}"
                            method="GET"
                            class="search-form"
                        >

                            @if ($blocks->count() != 4)
                                <ul class="nav navbar-right panel_toolbox">
                                    <li class="top-button">
                                        <a href="{{ admin_url('footer-blocks/create') }}"
                                        class="btn btn-small btn-primary">
                                            Create New
                                        </a>
                                    </li>
                                </ul>
                            @endif

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
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="table-responsive pw-table">
                        {{$blocks->links('pagination::bootstrap-4')}}
                        @if(!empty($blocks->count()))
                            <table class="table table-striped jambo_table bulk_action table-bordered-">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Order</th>
                                    <th>Number of links</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody class="sortable" data-sorturl="{{ admin_url('footer-blocks/sort') }}">
                                @foreach($blocks as $block)
                                    <tr id="item-{{ $block->footer_block_id }}">
                                        <td>{{ $block->footer_block_title }}</td>
                                        <td>{{ $block->footer_block_order }}</td>
                                        <td>{{ count($block->links) }}</td>
                                        <td align="center">
                                            <a href="{{admin_url('footer-blocks/'.$block->footer_block_id.'/edit')}}" class="btn btn-small btn-primary">Edit</a>
                                            | <a href="#" class="btn btn-small btn-danger modal-toggle"
                                                 data-item-id="{{ $block->footer_block_id }}"
                                                 data-toggle="modal"
                                                 data-modal-type="delete"
                                                 data-modal-title="Delete"
                                                 data-modal-size="small"
                                                 data-delete-type="footer-blocks"
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