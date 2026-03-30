@extends('backend.layouts.master')

@section('admin-content')

<div class="row current_url" data-url="{{admin_url('enquiries')}}">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel pw">
            <div class="x_title">
                <h2></h2>
                <ul class="nav navbar-right panel_toolbox">
                  <li class="top-button"><a href="{{admin_url('enquiries')}}" class="btn btn-small btn-primary">View enquiries</a></li>
                   @if($enquiry->category == 'Property Enquiry')
                        @if(!$enquiry->lead_emails)
                            <li class="top-button"><a href="{{admin_url('enquiries/set-automation/'.$enquiry->id.'/property')}}" class="btn btn-small btn-primary">Enroll Into Automated Emails</a></li>
                        @endif
                   @endif
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="x_panel pw-inner-tabs">
                    @include('backend.enquiries.enquiry-details')
                </div>
                
                <div class="x_panel pw-inner-tabs">
                    <div class="x_title">
                        <h2>Add Notes</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content pw-open">
                        <div>
                            <div class="control-form">
                               
                                    <form
                                        action="{{ route('enquiries.addnote-update', $enquiry->id) }}"
                                        method="POST"
                                    >
                                        @csrf
                                        @method('PUT')

                                        <div class="control-form">
                                            <label>Status</label>
                                            <select
                                                name="e_status"
                                                id="id-e_status"
                                                class="form-control select-pw"
                                            >
                                                @foreach(e_states() as $key => $label)
                                                    <option
                                                        value="{{ $key }}"
                                                        {{ old('e_status', $enquiry->e_status) == $key ? 'selected' : '' }}
                                                    >
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Your Notes</label>
                                            <textarea
                                                name="add_notes"
                                                id="id-add_notes"
                                                class="mceEditor description"
                                                placeholder="Please enter..."
                                                maxlength="60000"
                                            >{{ old('add_notes', $enquiry->add_notes) }}</textarea>
                                        </div>

                                        <button
                                            type="submit"
                                            class="btn btn-large btn-primary"
                                            name="action"
                                            value="update_enquiry"
                                        >
                                            Save
                                        </button>
                                    </form>
                              
                            </div>
                        </div>
                    </div>
                </div>
            </div> 

                <!-- <div class="x_panel pw-inner-tabs">
                    <div class="x_title">
                        <h2>Reply Message</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content pw-open">
                        <div>
                            <div class="control-form">
                                @php if(!empty($enquiry->reply_message)){ @endphp
                                    <p>{!!$enquiry->reply_message!!}</p>

                                    <div class="form-group sticky-buttons">
                                        @include('backend.enquiries.sticky-buttons')
                                    </div>

                                @php }else{ @endphp
                                    <form
                                        action="{{ route('enquiries.update', $enquiry->id) }}"
                                        method="POST"
                                        data-toggle="validator"
                                    >
                                        @csrf
                                        @method('PUT')

                                        <div class="form-group">
                                            <label>
                                                Your Reply {!! required_label() !!}
                                            </label>

                                            <textarea
                                                name="reply_message"
                                                id="reply_message"
                                                class="style-1 description"
                                                style="width:100%"
                                                placeholder="Please enter..."
                                                required
                                            >{{ old('reply_message') }}</textarea>

                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        </div>

                                        <div class="form-group sticky-buttons">
                                            @if($enquiry->archived_at === null)
                                                <button type="submit" class="btn btn-primary">
                                                    Reply
                                                </button>
                                            @endif

                                            @include('backend.enquiries.sticky-buttons')
                                        </div>
                                    </form>
                                @php } @endphp
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>

       

<!-- If Automations In Place - Show Stats / Info -->
@if($enquiry->lead_emails && !$enquiry->lead_emails->messages->isEmpty())
    <!-- <div class="x_panel pw">
        <div class="x_title">
            <h2>Lead Emails</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="x_panel pw-inner-tabs"> -->
                @if(!$enquiry->lead_emails->messages->isEmpty())
                    <div class="scroll">
                        <!-- <table class="table table-striped jambo_table bulk_action table-bordered-">
                            Table content...
                        </table> -->
                        @if($enquiry->lead_emails->lead_is_subscribed == 'y')
                            <p>Next message scheduled for <strong>{{ $enquiry->lead_emails->next_message }}</strong></p>
                        @endif
                    </div>
                @else
                    <p>First message scheduled for <strong>{{ $enquiry->lead_emails->next_message }}</strong></p>
                    @if($enquiry->lead_emails->lead_is_subscribed == 'y')
                        <a href="{{admin_url('enquiries/remove-automation/'.$enquiry->id.'')}}" class="btn btn-small btn-primary">Opt Out</a>
                    @else
                        <strong>Opted out on : {{ $enquiry->lead_emails->last_updated }}</strong>
                    @endif
                @endif
            </div>
        </div>
    <!-- </div> -->
@endif

    <!-- </div>


</div> -->



@endsection
