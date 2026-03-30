@extends('backend.layouts.master')

@section('admin-content')

    <form method="post" action="{{ url('admin/footer-blocks/'. $item->footer_block_id) }}" data-toggle="validator">
        @method('PUT')
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
                                <h2>Block Detail</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content pw-open">
                                <div class="xpw-fields">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label>Title: {!! required_label() !!}</label>
                                                <input name="title" value="{{ $item->footer_block_title }}" type="text" class="form-control" placeholder="Title" required>
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
        <div class="form-group sticky-buttons">
            <button type="submit" class="btn btn-large btn-primary" name="action" >Save</button>
            <a href="{{admin_url('footer-blocks')}}" class="btn btn-default btn-spacing">Cancel <span>and Return</span></a>
        </div>
        @csrf
    </form>


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
                                <h2>Links</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li class="top-button"><a href="{{admin_url('footer-blocks/links/create/'.$item->footer_block_id.'')}}" class="btn btn-small btn-primary">Create New</a></li>
                                        <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a></li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                @if(!$item->links->isEmpty())
                                    <div class="x_content pw-open">
                                    <div class="xpw-fields">
                                        <div class="table-responsive pw-table">
                                            <table class="table table-striped jambo_table bulk_action table-bordered-">
                                                <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>URL</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody class="sortable" data-sorturl="{{ admin_url('footer-links/sort') }}">
                                                @foreach($item->links as $link)
                                                    <tr id="item-{{$link->footer_link_id}}">
                                                        <td>{{ $link->footer_link_title }}</td>
                                                        <td>{{ $link->footer_link_url }}</td>
                                                        <td class="text-center table-active-btn">
                                                            <a href="{{ url(admin_url('footer-blocks/links/'.$link->footer_link_id.'/edit')) }}" class="btn btn-small btn-primary">Edit</a>
                                                            | <a href="#" class="btn btn-small btn-danger modal-toggle"
                                                                 data-item-id="{{ $link->footer_link_id }}"
                                                                 data-toggle="modal"
                                                                 data-modal-type="delete"
                                                                 data-modal-title="Delete"
                                                                 data-modal-size="small"
                                                                 data-delete-type="footer-link"
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
                            </div>
                        </div>
                    </div>
                </div>
    </div>


@endsection
