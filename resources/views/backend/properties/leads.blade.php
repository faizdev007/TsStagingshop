@extends('backend.properties.template')

@section('property-content')
<div class="table-responsive pw-table">
    @if(count($property->propertyEnquiries))
    <table class="table table-striped jambo_table bulk_action table-bordered-">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ref.</th>
                <th>Type</th>
                <th>From</th>
                <th>Comments</th>
                <th>Date Added</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach( $property->propertyEnquiries as $lead )
            <tr>
                <td>{{$lead->id}}</td>
                <td class="ref-link"><a href="{{$lead->url}}">{{$lead->ref}}</a></td>
                <td>{{$lead->category}}</td>
                <td>{{$lead->email}}</td>
                <td>{{ $lead->message ?? '' }}</td>
                <td>{{$lead->display_date}}</td>
                <td class="text-center table-active-btn">
                    <a href="{{admin_url('properties/'.$property->id.'/response/'.$lead->id)}}" class="btn btn-small btn-primary" >
                        {{empty($lead->reply_message) ? 'Respond' : 'Review'}}</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <div class="no-data">
            There are no enquiries for this property, but the property has had {{ !empty($property_views) ? $property_views : 0 }} page views.
        </div>
    @endif
</div>

<div class="form-group sticky-buttons">
    @include('backend.properties.sticky-buttons')
</div>
@endsection
