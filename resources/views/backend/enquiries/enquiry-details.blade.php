<div class="x_title">
    <h2>Enquiry Details</h2>
    <ul class="nav navbar-right panel_toolbox">
        <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
        </li>
    </ul>
    <div class="clearfix"></div>
</div>
<div class="x_content pw-open">
    <div class="xpw-fields">
        <div class="row pw-table table-responsive">
            <table class="table table-bordered text-nowrap">
                <tbody>
                    <tr>
                        <th scope="row">Name:</th>
                        <td>{{ ucwords($enquiry->name) ?? '--' }}</td>
                        <th scope="row">Email:</th>
                        <td><a href="mailto:{{$enquiry->email ?? '#'}}">{{ $enquiry->email ?? '--' }}</a></td>
                    </tr>
                    <tr>
                        <th scope="row">Telephone:</th>
                        <td><a href="tel:{{$enquiry->telephone ?? '#'}}"> {{$enquiry->telephone ?? '--'}} </a></td>
                        <th scope="row">Status:</th>
                        <td>{{$enquiry->e_status ?? '--'}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Category:</th>
                        <td>{{$enquiry->category ?? '--'}}</td>
                        <th scope="row">Date Submitted:</th>
                        <td>{{$enquiry->display_date ?? '--'}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Data:</th>
                        <td>
                            @if($enquiry->data)
                                @php
                                    $data_array = json_decode($enquiry->data);
                                    foreach( $data_array as $key => $data){
                                        if($key=='preferred_date'){
                                            $key = 'Preferred date';
                                        }
                                        if(!empty($data)){
                                            $key = str_replace('_',' ',$key);
                                            echo ucwords($key).': '.ucwords(urldecode($data)).'<br />';
                                        }
                                    }
                                @endphp
                            @endif
                        </td>
                        <th scope="row">Branch:</th>
                        <td>{{ settings('branches_option') == 1 && $enquiry->branch && Auth::user()->role_id <= '2' ? $enquiry->branch->branch_name : '--'}}</td>
                    </tr>
                    <tr>
                        <th scope="row">From URL:</th>
                        <td colspan="3"><a href="{{$enquiry->url}}" target='_blank'>{{$enquiry->url ?? '--'}}</a></td>
                    </tr>
                    <tr>
                        <th scope="row" colspan="4">Message:</th>
                    </tr>
                    <tr>
                        <td colspan="4" rowspan="4" class="text-capitalize">{!! $enquiry->message ?? '--' !!}</td>
                    </tr>
                </tbody>
            </table>
            <!-- <div class="col-md-3">
                <div>
                    <div class="control-form">
                        <label for="name">Name:</label>
                        <div class="data-item">{{ $enquiry->name }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div>
                    <div class="control-form">
                        <label for="location">Email:</label>
                        <div class="data-item">{{ $enquiry->email }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div>
                    <div class="control-form">
                        <label for="location">Category:</label>
                        <div class="data-item">{{$enquiry->category}}</div>
                    </div>
                </div>
            </div> -->
            <!-- <div class="col-md-3">
                <div>
                    <div class="control-form">
                    <label>Status</label>
                    <select name="e_status"
                            id="id-e_status"
                            class="form-control select-pw">
                        @foreach(e_states() as $key => $value)
                            <option value="{{ $key }}"
                                {{ (string) old('e_status', $enquiry->e_status) === (string) $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                    </div>
                </div>
            </div> -->
            <!-- <div class="col-md-3">
                <div>
                    <div class="control-form">
                        <label for="location">Date Submitted:</label>
                        <div class="data-item">{{$enquiry->display_date}}</div>
                    </div>
                </div>
            </div>

            @if(!empty($enquiry->telephone))
            <div class="col-md-3">
                <div>
                    <div class="control-form">
                        <label for="location">Telephone:</label>
                        <div class="data-item">{{ $enquiry->telephone }}</div>
                    </div>
                </div>
            </div>@endif -->

            <!-- @if(!empty($enquiry->country))
            <div class="col-md-3">
                <div>
                    <div class="control-form">
                        <label for="location">Country:</label>
                        <div class="data-item">{{ $enquiry->country }}</div>
                    </div>
                </div>
            </div>@endif

            @if(settings('branches_option') == 1 && $enquiry->branch && Auth::user()->role_id <= '2')
            <div class="col-md-3">
                <div>
                    <div class="control-form">
                        <label for="location">Branch:</label>
                        <div class="data-item">{{ $enquiry->branch->branch_name }}</div>
                    </div>
                </div>
            </div>
            @endif

            <div class="col-md-6">
                <div>
                    <div class="control-form">
                        <label for="location">From URL:</label>
                        <div class="data-item"><a href="{{$enquiry->url}}" target='_blank'>{{$enquiry->url}}</a></div>
                    </div>
                </div>
            </div>

            @if( !empty($enquiry->data) )
            <div class="col-md-12">
                <div>
                    <div class="control-form">
                        <label for="location">Data:</label>
                        <div class="data-item">
                            @php
                                $data_array = json_decode($enquiry->data);
                                foreach( $data_array as $key => $data){
                                    if($key=='preferred_date'){
                                        $key = 'Preferred date';
                                    }
                                    if(!empty($data)){
                                        $key = str_replace('_',' ',$key);
                                        echo ucwords($key).': '.ucwords(urldecode($data)).'<br/>';
                                    }
                                }
                            @endphp
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="col-md-12">
                <div>
                    <div class="control-form">
                        <label for="location">Message:</label>
                        <div class="data-item">{!! $enquiry->message !!}</div>
                    </div>
                </div>
            </div> -->

        </div>
    </div>
</div>

@if(!empty($enquiry->property->user))
<div class="x_title">
    <h2>Agent Details</h2>
    <div class="clearfix"></div>
</div>
<div class="x_content">
    <div class="xpw-fields">
        <div class="row">
            <table class="table table-bordered text-nowrap">
                <tbody>
                    <tr>
                        <th scope="row">Agent Name:</th>
                        <td>{{$enquiry->property->user->name ?? '--'}}</td>
                        <th scope="row">Email:</th>
                        <td>{{$enquiry->property->user->email ?? '--'}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Telephone:</th>
                        <td>{{ $enquiry->property->user->telephone ?? '--' }}</td>
                        <th scope="row"></th>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <!-- 
                <div class="col-md-3">
                    <div>
                        <div class="control-form">
                            <label for="name">Agent Name:</label>
                            <div class="data-item">{{$enquiry->property->user->name}}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div>
                        <div class="control-form">
                            <label for="location">Email:</label>
                            <div class="data-item">{{$enquiry->property->user->email}}</div>
                        </div>
                    </div>
                </div>
                    

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div>
                            <div class="control-form">
                                <label for="location">Telephone:</label>
                                <div class="data-item">{{$enquiry->property->user->telephone}}</div>
                            </div>
                        </div>
                    </div>
                </div> 
            -->
        </div>
    </div>

    
</div>
@endif


<!-- <div class="x_title">
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
</div> -->