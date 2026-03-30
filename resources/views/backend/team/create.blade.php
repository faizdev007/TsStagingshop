@extends('backend.layouts.master')

@section('admin-content')

<form action="{{admin_url('team')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="xpw-fields">
        <div class="row">
            <div class="col-md-6">
                <div class="control-form">
                    <label for="fullname">Name: {!! required_label() !!}</label>
                    <input type="text" id="text_line1" value="{{ old('team_member_name') }}" class="form-control" name="team_member_name" placeholder="Please enter..." />
                </div>
            </div>

            <div class="col-md-6">
                <div class="control-form">
                    <label for="fullname">Job Title / Role: {!! required_label() !!}</label>
                    <input type="text" id="text_line2" value="{{ old('team_member_role') }}" class="form-control" name="team_member_role" placeholder="Please enter..." />
                </div>
            </div>

            <div class="col-md-6">
                <div class="control-form">
                    <label for="fullname">Phone Number: {!! required_label() !!}</label>
                    <input type="text" id="text_line2" value="{{ old('team_member_phone') }}" class="form-control" name="team_member_phone" placeholder="Please enter..." />
                </div>
            </div>

            <div class="col-md-6">
                <div class="control-form">
                    <label for="fullname">Email Address: {!! required_label() !!}</label>
                    <input type="text" id="text_line2" value="{{ old('team_member_email') }}" class="form-control" name="team_member_email" placeholder="Please enter..." />
                </div>
            </div>
            <div class="col-md-6">
                <div class="control-form">
                    <label for="fullname">User: {!! required_label() !!}</label>
                    <select name="user_id" class="form-control">
                        <option value="">Select User</option>
                        @foreach($teammember as $key=>$single)
                            <option value="{{$single->id}}" {{ old('user_id') == $single->id ? 'selected' : ''}}>{{$single->name}} ({{$single->role->role_title}})</option>
                        @endforeach                    
                    </select>
                </div>
            </div>
            <!-- <div class="col-md-6 d-none">
                <div class="control-form">
                    <label for="fullname">Profile Image: </label>
                    <input type="file" id="text_line2" value="{{ old('team_member_photo') }}" class="form-control" name="location_photo" placeholder="Please enter..." />
                </div>
            </div> -->

            <div class="col-md-6">
                <div class="control-form">
                    <label for="fullname">Languages: </label>
                    <input type="text" id="text_line5" value="{{ old('team_member_languages')}}" class="form-control" name="team_member_languages" placeholder="Please enter..." />
                </div>
            </div>
            <div class="col-md-6">
                <div class="control-form">
                    <label for="fullname">Experience: </label>
                    <input type="text" id="text_line6" value="{{old('team_member_experience')}}" class="form-control" name="team_member_experience" placeholder="Please enter..." />
                </div>
            </div>

            <div class="col-md-6">
                <div class="control-form">
                    <label for="fullname">Dubai Broker License: </label>
                    <input type="text" id="text_line6" value="{{old('team_member_broker_licence')}}" class="form-control" name="team_member_broker_licence" placeholder="Please enter..." />
                </div>
            </div>

            <div class="col-md-12">
                <div class="control-form">
                    <label>Areas of Experties:</label>
                    <select
                        name="team_member_experties[]"
                        class="form-control select-pw-ajax-communities"
                        multiple
                    >
                        {{-- Options loaded via AJAX --}}
                    </select>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label for="fullname">Description: {!! required_label() !!}</label>
                    <textarea
                        name="team_member_description"
                        id="content"
                        class="mceEditor description"
                        style="width:100%"
                        placeholder="Please enter..."
                        maxlength="60000"
                    >{{ old('team_member_description') }}</textarea>
                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>

        </div>

        <div class="form-group sticky-buttons">
            <button type="submit" class="btn btn-large btn-primary" >Save</button>
            <a href="{{admin_url('team')}}" class="btn btn-default btn-spacing">Cancel <span>and Return</span></a>
        </div>

    </div>

</form>
<script>
    document.addEventListener('DOMContentLoaded',function(){
        var base_url = $('.base-url').val();

        const token = document.querySelector('meta[name="csrf-token"]');

        if (!token) {
            console.error('CSRF token meta tag not found');
            return;
        }

        /*------------------------------------
        DROPDOWN FORMAT
        ------------------------------------*/
        function formatRepo (repo) {

        if (repo.loading) return repo.name;
        var markup = "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__meta'>"
        if (repo.name) {
            markup += "<div class='select2-result-repository__description'><strong>" + repo.name+'</strong>';
        }
        markup += "</div></div>";
        return markup;
        }

        function formatRepoSelection (repo) {
        if (repo.name === undefined) {
            return repo.text;
        } else {
            return repo.name;
        }
        }
        /*------------------------------------
            SELECT 2 - AJAX - Communities
        ------------------------------------*/
        $(".select-pw-ajax-communities").select2({
            multiple: true,
            //minimumInputLength: 1,
            //maximumSelectionLength: 1,
            ajax: {
                headers: {
                    'X-CSRF-TOKEN': token.getAttribute('content')
                },
                url: base_url+"/users/get/filtercommunities",
                dataType: 'json',
                type: 'POST',
                delay: 250,
                data: function (params) {
                    //console.log(params);
                    var query = {
                      q: params.term,
                      page: params.page || 1,
                      type: 'public'
                    }
                    return query;
                    /*
                    return {
                      q: params.term, // search term
                      page: params.page
                    };
                    */
                },
                /*
                processResults: function(data) {
                    console.log(data);
                    return {
                        results: data.items
                    };
                },
                */
                processResults: function (data, params) {
                    var query = {
                        results: data.items,
                        pagination: {
                            more: (data.page * 10) < data.total_count
                        }
                    }
                    return query;
                },
    
                cache: true,
            },
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            templateResult: formatRepo, // omitted for brevity, see the source of this page
            templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
        });
    });
</script>
@endsection

