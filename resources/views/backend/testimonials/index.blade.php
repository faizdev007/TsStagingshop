@extends('backend.layouts.master')

@section('admin-content')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel pw">
            <div class="x_title">
                <div class="search-form-style-1">
                    @include('backend.testimonials.search-form')
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="table-responsive pw-table">
                    {{$testimonials->links('pagination::bootstrap-4')}}

                    @if(!empty($testimonials->count()))
                    <table class="table table-striped jambo_table bulk_action table-bordered-">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Quote</th>
                                <th>Rating</th>
                                <th>Date</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody  id="testimonials-sort" data-sorturl="{{ admin_url('testimonials/sort') }}">
                            @foreach($testimonials as $testimonial)
                                <tr id="item-{{$testimonial->id}}">
                                    <td>{{strip_tags($testimonial->name)}}</td>
                                    <td>{{strip_tags($testimonial->location)}}</td>
                                    <td>{{Str::limit(strip_tags($testimonial->quote), 70)}}</td>
                                    <td>{{strip_tags($testimonial->rating ?? '0.0')}}</td>
                                    <td>{{admin_date($testimonial->date)}}</td>
                                    <td class="text-center table-active-btn">

                                        <a href="{{admin_url('testimonials/'.$testimonial->id.'/edit')}}" class="btn btn-small btn-primary">Edit</a>

                                        @if(0)
                                        <form action="{{admin_url('testimonials/'.$testimonial->id)}}" method="POST" onsubmit="return confirm('Are you sure to delete slide \'{{$testimonial->name}}\'?')">
                                            @csrf
                                            <input name="_method" type="hidden" value="DELETE">
                                             |
                                            <button type="submit" class="btn btn-small btn-danger">Delete</button>
                                        </form>
                                        @endif

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
<script src="{{asset('assets/admin/build/js/pw-testimonials.js')}}"></script>
@endpush
