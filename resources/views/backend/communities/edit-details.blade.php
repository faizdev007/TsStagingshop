<form action="{{admin_url($folder.'/'.$item->id)}}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="x_panel pw-inner-tabs">
        <div class="x_title">
            <h2>Settings</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content pw-open">

            <div class="xpw-fields">
                <div class="row">
                    <div class="col-md-4">
                        <div class="control-form">
                            <label>Is Publish?: {!! required_label() !!}</label>
                            <select name="is_publish" class="form-control select-pw">
                                <option value="0" {{ (old('is_publish', $item->is_publish) == 0) ? 'selected' : '' }}>
                                    No
                                </option>
                                <option value="1" {{ (old('is_publish', $item->is_publish) == 1) ? 'selected' : '' }}>
                                    Yes
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
    <div class="x_panel pw-inner-tabs">
        <div class="x_title">
            <h2>Info</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content pw-open">

            <div class="xpw-fields">
                <div class="row">
                    <div class="col-md-4">
                        <div class="control-form">
                            <label for="fullname">Name: {!! required_label() !!}</label>
                            <input type="text" id="name" value="{{$item->name}}" class="form-control" name="name" placeholder="Please enter..." />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="content">Content: {!! required_label() !!}</label>
                        <textarea
                            name="content"
                            id="id-content"
                            class="mceEditor description"
                            placeholder="Please enter..."
                            maxlength="60000"
                        >{{ old('content', $item->content) }}</textarea>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="form-group sticky-buttons">
        <button type="submit" class="btn btn-large btn-primary" >Save</button>
        <a href="{{admin_url($folder)}}" class="btn btn-default btn-spacing">Cancel <span>and Return</span></a>

        @if(  Auth::user()->role_id == '1' )
        <a href="{{admin_url($folder.'/'.$item->id.'/delete')}}" class="confirm-action btn btn-danger btn-spacing" title="permantly delete this entry">
            <i class="fas fa-trash"></i> Delete
        </a>@endif
    </div>
</form>
