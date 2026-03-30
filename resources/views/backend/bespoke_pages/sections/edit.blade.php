@extends('backend.layouts.master')

@section('admin-content')

<form method="post" action="{{ admin_url('bespoke-section/'.$section->id.'/edit') }}" data-toggle="validator">
        <input type="hidden" name="type" value="{{ $section->type}}">
        @csrf
        <div class="row current_url" data-url="{{ admin_url('bespoke-pages') }}">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel pw">
                    <div class="x_title">
                        <h2><br/></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content ">
                        <p class="text-muted font-13 m-b-30"><br/></p>
                        <div class="x_panel pw-inner-tabs">
                            <div class="x_title">
                                <h2>Edit Component - {{ $section->title }}</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li class="top-button"><a href="{{ admin_url('bespoke-pages/'.$section->page_id.'/edit?tab=components') }}" class="btn btn-small btn-primary">Back</a></li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content pw-open">
                                <div class="xpw-fields">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label>Title: {!! required_label() !!}</label>
                                                <input name="title" type="text" class="form-control" value="{{ $section->title }}" placeholder="Section Title" required>
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            </div>
                                        </div>
                                    </div>
                                    @if($section->type == 'text_block')
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label>Content: {!! required_label() !!}</label>
                                                    <textarea id="content" name="content" class="mceEditor description" placeholder="Please enter" required>{{ $section->content }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($section->type == 'local_information' || $section->type == 'things_to_do' || $section->type == 'popular_locations')
                                        @if($section->section_contents->count() > 0)
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <div class="table-responsive pw-table">
                                                        <table class="table table-striped jambo_table bulk_action table-bordered-">
                                                            <thead>
                                                                <tr>
                                                                    <th>Title</th>
                                                                    <th>Content</th>
                                                                    <th class="text-center">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="sortable" data-sorturl="{{ admin_url('page-content/sort/'.$section->id) }}">
                                                                @foreach($section->section_contents as $sect)
                                                                    <tr id="item-{{$sect->id}}">
                                                                        <td>{{strip_tags($sect->title)}}</td>
                                                                        <td>{{Str::limit(strip_tags($sect->content), 70)}}</td>
                                                                        <td class="text-center table-active-btn">
                                                                            <a href="{{admin_url('bespoke-section-content/'.$sect->id.'/edit')}}" class="btn btn-small btn-primary">Edit</a> |
                                                                            <a href="#" class="btn btn-small btn-danger modal-toggle"
                                                                               data-item-id="{{ $sect->id }}"
                                                                               data-toggle="modal"
                                                                               data-modal-type="delete"
                                                                               data-modal-title="Delete"
                                                                               data-modal-size="small"
                                                                               data-delete-type="section-content"
                                                                               data-target="#global-modal">Delete</a>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="row">
                                            <div class="u-pl1">
                                                <a class="btn btn-large btn-primary create-block" data-block-type="block_type" href="#">Create New Block</a>
                                            </div>
                                        </div>
                                        <div id="block_type" style="display: none;">
                                            <div class="row u-mt1 u-mb1">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <div class="form-group">
                                                        <label>Title: {!! required_label() !!}</label>
                                                        <input name="section_title" type="text" class="form-control" value="{{ old('section_title') }}" placeholder="Section Title" required>
                                                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($section->type == 'popular_locations')
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <div class="form-group">
                                                            <label>URL: {!! required_label() !!}</label>
                                                            <input name="url" type="text" class="form-control" value="{{ old('url') }}" placeholder="Page URL" required>
                                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label>Content: {!! required_label() !!}</label>
                                                        <textarea id="content" name="section_content" class="mceEditor description" placeholder="Please enter" required>{{ old('section_content') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group sticky-buttons">
            <button type="submit" class="btn btn-large btn-primary" name="action" >Save</button>
            <a href="{{admin_url('bespoke-pages/'.$section->page->id.'/edit?tab=sections')}}" class="btn btn-default btn-spacing">Cancel <span>and Return</span></a>
        </div>
        @csrf
    </form>
@endsection
