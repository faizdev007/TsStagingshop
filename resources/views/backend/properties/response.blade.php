@extends('backend.properties.template')

@section('property-content')

@if(!empty($enquiry))

    <div class="x_content">
        <div class="x_panel pw-inner-tabs">
            @include('backend.enquiries.enquiry-details')
        </div>

        <div class="x_panel pw-inner-tabs">
            <div class="x_title">
                <h2>Reply Message</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div>
                    <div class="control-form">
                        @php if(!empty($enquiry->reply_message)){ @endphp
                            <p>{!!$enquiry->reply_message!!}</p>
                        @php }else{ @endphp
                            <form action="{{ route('enquiries.update', $enquiry->id) }}"
                                method="POST">

                                @csrf
                                @method('PUT')

                                <textarea name="reply_message"
                                        id="reply_message"
                                        class="style-1 description"
                                        style="width:100%"
                                        placeholder="Please enter...">{{ old('reply_message') }}</textarea>

                                <div class="form-group sticky-buttons">
                                    <button type="submit" class="btn btn-primary">
                                        Reply
                                    </button>

                                    <a href="{{ admin_url('properties/'.$property->id.'/leads') }}"
                                    class="btn btn-default btn-spacing">
                                        Cancel and Return
                                    </a>
                                </div>

                            </form>
                        @php } @endphp
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="no-data">
        No data found.
    </div>
@endif

@endsection
