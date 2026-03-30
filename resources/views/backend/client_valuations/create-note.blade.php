@extends('backend.layouts.master')

@section('admin-content')

    <form method="post" action="{{ url('admin/market-valuation/note/'.$id) }}" data-toggle="validator">
        <div class="x_panel pw-inner-tabs">
            <div class="xpw-fields">
                <div class="row current_url" data-url="{{admin_url('market-valuation')}}">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <legend>New Note</legend>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="fullname">Title: {!! required_label() !!}</label>
                            <input type="text" name="client_valuation_note_title" class="form-control" value="{{ old('client_valuation_note_title') }}" placeholder="Note Title" required>
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="slug">Message: {!! required_label() !!}</label>
                            <textarea
                                name="client_valuation_text"
                                id="content"
                                class="mceEditor description"
                                style="width:100%"
                                placeholder="Please enter..."
                                maxlength="60000"
                            >{{ old('client_valuation_text') }}</textarea>
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="client_valuation_id" value="{{ $id }}">
            @csrf

            <div class="form-group sticky-buttons">
                <button type="submit" class="btn btn-large btn-primary" >Save</button>
                <a href="{{admin_url('pages')}}" class="btn btn-default btn-spacing">Cancel <span>and Return</span></a>
            </div>
        </div>
    </form>


@endsection