@extends('backend.layouts.master')

@section('admin-content')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel pw">
                <div class="x_title">
                    <h2>&nbsp;</h2>
                    <div class="search-form-style-1">
                        <ul class="nav navbar-right panel_toolbox">
                            <li class="top-button"><a href="{{ admin_url('market-valuation/why-list/create') }}" class="btn btn-small btn-primary">Create New</a></li>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="table-responsive pw-table">
                        @if(!empty($items->count()))
                            <table class="table table-striped jambo_table bulk_action table-bordered-">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Content</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody class="sortable" data-sorturl="{{ admin_url('why-list/sort') }}">
                                @foreach($items as $item)
                                    <tr id="item-{{$item->id}}">
                                        <td>
                                            {{ $item->title }}
                                        </td>
                                        <td>
                                            {{ Str::limit($item->content, 90) }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{admin_url('market-valuation/why-list/edit/'.$item->id)}}" class="btn btn-small btn-primary">Edit</a> |
                                            <a href="#" class="btn btn-small btn-danger modal-toggle"
                                               data-item-id="{{ $item->id }}"
                                               data-toggle="modal"
                                               data-modal-type="delete"
                                               data-modal-title="Delete"
                                               data-modal-size="small"
                                               data-delete-type="why-list-item"
                                               data-target="#global-modal">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="no-data">
                                No items created.
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
