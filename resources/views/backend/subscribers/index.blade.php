@extends('backend.layouts.master')

@section('admin-content')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel pw">
            <div class="x_title">
                <div class="search-form-style-1">
                    @include('backend.subscribers.search-form')
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <p class="text-muted font-13 m-b-30"></p>
                <div class="table-responsive pw-table">
                    @if(count($subscribers))
                    <div class="scroll">
                        <table class="table table-striped jambo_table bulk_action table-bordered-">
                            <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Date</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($subscribers as $subscriber)
                            <tr >
                                <td width="80%" class="text-capitalize text-nowrap">{{ $subscriber->fullname ?? '--' }}</td>
                                <td width="80%" class="text-nowrap"><a href="mailto:{{ $subscriber->email ?? '#' }}">{{ $subscriber->email ?? '--' }}</a></td>
                                <td width="80%" class="text-nowrap"><a href="tel:{{ $subscriber->telephone ?? '#' }}">{{ $subscriber->telephone ?? '--' }}</a></td>
                                <td width="10%" class="text-nowrap">{{ $subscriber->created_at }}</td>
                                <td width="10%" class="text-center">
                                    <a href="#" class="btn btn-small btn-danger modal-toggle"
                                       data-modal-title="Delete Subscriber"
                                       data-item-id="{{ $subscriber->id }}"
                                       data-toggle="modal"
                                       data-modal-type="delete"
                                       data-modal-size="small"
                                       data-delete-type="subscribers"
                                       data-target="#global-modal">Delete</a>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                        <div class="hide">
                            {{$subscribers->links('pagination::bootstrap-4')}}
                        </div>
                    </div>
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

@push('footerscripts')
<script src="{{asset('assets/admin/build/vendors/jquery/jquery.jscroll.min.js')}}"></script>
<script src="{{asset('assets/admin/build/js/pw-lazy-pagination.js')}}"></script>
@endpush
