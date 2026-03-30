@extends('backend.layouts.master')

@section('admin-content')

    <form method="post" action="{{ url('admin/search-content/'.$item->id) }}" data-toggle="validator">
        <div class="row">
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
                                <h2>Page Detail</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content pw-open">
                                <div class="xpw-fields">
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label>Title: {!! required_label() !!}</label>
                                                <input name="content_title" value="{{ $item->content_title }}" type="text" class="form-control" placeholder="Content Title" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label>URL: {!! required_label() !!}</label>
                                                <input name="content_url" type="text" class="form-control" value="{{ $item->content_url }}" placeholder="Content URL" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label>Content {!! required_label() !!}</label>
                                                <textarea id="content" name="content" class="mceEditor description" placeholder="Please enter" maxlength="6000" required>{{ $item->content }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    @if(!empty($item->blocks()))
                                        <input type="hidden" class="edit_num_blocks" value="{{ $item->blocks->count() }}">
                                        @foreach($item->blocks as $content_block)
                                            <input type="hidden" name="block[{{ $content_block->id }}][id]" value="{{ $content_block->id }}">
                                            <div class="row">
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div class="x_panel pw">
                                                        <div class="x_content ">
                                                            <p class="text-muted font-13 m-b-30"><br/></p>
                                                            <div class="x_panel pw-inner-tabs">
                                                                <div class="x_title">
                                                                    <h2>{{ $content_block->heading }}</h2>
                                                                    <ul class="nav navbar-right panel_toolbox">
                                                                        <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a></li>
                                                                    </ul>
                                                                    <div class="clearfix"></div>
                                                                </div>
                                                                <div class="x_content pw-open">
                                                                    <div class="xpw-fields">
                                                                        <div class="row">
                                                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                                <div class="form-group">
                                                                                    <label>Heading: *</label>
                                                                                    <input name="block[{{ $content_block->id }}][heading]" value="{{ $content_block->heading }}" type="text" class="form-control" placeholder="Content Title" required>
                                                                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                                <div class="form-group">
                                                                                    <label>Content *</label>
                                                                                    <textarea name="content-{{ $content_block->id }}" class="mceEditor description" placeholder="Please enter" required>{{ $content_block->content }}</textarea>
                                                                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="text-center">
                                            <a href="#" class="btn btn-small btn-danger modal-toggle"
                                               data-item-id="{{ $content_block->id }}"
                                               data-toggle="modal"
                                               data-modal-type="delete"
                                               data-modal-title="Delete"
                                               data-modal-size="small"
                                               data-delete-type="search-content-block"
                                               data-target="#global-modal">Delete Block</a>
                                        </div><!-- /.text-center -->
                                    </div>
                                </div>
                                @endforeach
                                @endif
                                    <div class="additional-blocks">
                                        <!-- jQuery Populated -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <a class="btn btn-default add-search-blocks" href="#">Add More Content Blocks</a>
            </div>
        </div> -->


        <div class="form-group sticky-buttons">
            <button type="submit" class="btn btn-large btn-primary" name="action" >Save</button>
            <a href="{{admin_url('search-content')}}" class="btn btn-default btn-spacing">Cancel <span>and Return</span></a>
        </div>
        @csrf
    </form>

@endsection
