@extends('backend.layouts.master')

@section('admin-content')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel pw">
            <div class="x_title">
                <h2><br/></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="x_panel pw-inner-tabs">
                    <div class="x_title">
                        <h2>Article Fields</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content pw-open">

                    	<form action="{{ route('news.store') }}"
                            method="POST"
                            data-toggle="validator">

                            @csrf

                            <div class="xpw-fields">
                                <div class="row">

                                    {{-- Title --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="title">Title: {!! required_label() !!}</label>
                                            <input type="text"
                                                name="title"
                                                id="title"
                                                class="form-control"
                                                placeholder="Please enter..."
                                                value="{{ old('title') }}"
                                                required>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        </div>
                                    </div>

                                    {{-- Translated Titles --}}
                                    @if(settings('translations') && isset($languages))
                                        @foreach($languages as $k => $v)
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Title ({{ $k }}):</label>
                                                    <input type="text"
                                                        name="title_{{ $v }}"
                                                        class="form-control"
                                                        placeholder="Please enter..."
                                                        value="{{ old('title_'.$v) }}"
                                                        required>
                                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif

                                    {{-- Content --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="content">Content: {!! required_label() !!}</label>
                                            <textarea name="content"
                                                    id="content"
                                                    class="mceEditor description"
                                                    style="width:100%"
                                                    placeholder="Please enter..."
                                                    maxlength="60000"
                                                    required>{{ old('content') }}</textarea>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        </div>
                                    </div>

                                    {{-- Translated Content --}}
                                    @if(settings('translations') && isset($languages))
                                        @foreach($languages as $k => $v)
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Content ({{ $k }}):</label>
                                                    <textarea name="content_{{ $v }}"
                                                            class="mceEditor description"
                                                            style="width:100%"
                                                            placeholder="Please enter..."
                                                            maxlength="60000"
                                                            required>{{ old('content_'.$v) }}</textarea>
                                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif

                                    {{-- Status --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">Status: {!! required_label() !!}</label>
                                            <select name="status"
                                                    id="status"
                                                    class="form-control select-pw"
                                                    required>
                                                <option value="">Please select...</option>
                                                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                                            </select>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        </div>
                                    </div>

                                    {{-- Date Published --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date_published">Date Published: {!! required_label() !!}</label>
                                            <input type="text"
                                                name="date_published"
                                                id="date_published"
                                                class="form-control datepicker"
                                                placeholder="Please enter..."
                                                autocomplete="off"
                                                value="{{ old('date_published') }}">
                                        </div>
                                    </div>

                                    {{-- Categories --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="id-tags">Categories</label>
                                            <select id="id-tags"
                                                    name="location_tags[]"
                                                    class="form-control select-pw">
                                                <option value="">Please enter...</option>

                                                @if(count($postTags))
                                                    @foreach($postTags as $id => $tag)
                                                        <option value="{{ $id }}"
                                                            {{ collect(old('location_tags'))->contains($id) ? 'selected' : '' }}>
                                                            {{ ucwords($tag) }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                {{-- Buttons --}}
                                <div class="form-group sticky-buttons">
                                    <button type="submit" class="btn btn-large btn-primary">Create</button>
                                    <a href="{{ admin_url('news') }}"
                                    class="btn btn-default btn-spacing">
                                        Cancel and Return
                                    </a>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@push('footerscripts')
    <script src="{{asset('assets/admin/build/js/pw-select2-ajax.js')}}"></script>
@endpush
