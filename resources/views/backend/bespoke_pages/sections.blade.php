<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel pw">
            <div class="x_title">
                <h2>Components</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li class="top-button"><a href="#" class="btn btn-small btn-primary add-section">Add New Component</a></li>
                </ul>
                <div class="clearfix"></div>
                <small class="u-block">You are able to reorder the components by dragging/dropping</small>
            </div>
            <div class="section-options" style="display: none;">
                <div class="x_content">
                    <form method="post" action="{{ admin_url('create-section') }}">
                        @csrf
                        <div class="form-group">
                            <label>Component Type</label>
                            <select class="form-control select-pw section_type" name="section_type" data-placeholder="Please choose...">
                                <option selected="selected"></option>
                                @foreach($sections as $k => $v)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-small btn-primary create-section">Create Component</button>
                        </div>
                        <input type="hidden" name="page_id" value="{{ $page->id }}">
                    </form>
                </div>
            </div>
            <div class="x_content">
                <div class="table-responsive pw-table">
                    @if($page->sections->count() > 0)
                    <table class="table table-striped jambo_table bulk_action table-bordered-">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Type</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody class="sortable" data-sorturl="{{ admin_url('page-sections/sort') }}">
                                @foreach($page->sections as $section)
                                    <tr id="item-{{$section->id}}">
                                        <td>{{ ucwords($section->title) }}</td>
                                        <td>{{ ucwords(str_replace('_', ' ', $section->type)) }}</td>
                                        <td class="text-center table-active-btn">
                                            @if($section->type !== 'latest_properties' && $section->type !== 'news')
                                                <a href="{{ admin_url('bespoke-section/'.$section->id.'/edit') }}" class="btn btn-small btn-primary">Edit</a> |
                                            @endif
                                                <a href="#" class="btn btn-small btn-danger modal-toggle"
                                                   data-item-id="{{ $section->id }}"
                                                   data-toggle="modal"
                                                   data-modal-type="delete"
                                                   data-modal-title="Delete"
                                                   data-modal-size="small"
                                                   data-delete-type="bespoke-section"
                                                   data-target="#global-modal">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="no-data">
                            No components added. Create these using the Add New Component button
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group sticky-buttons">
    <button type="submit" class="btn btn-large btn-primary" >Save</button>
    <a href="{{admin_url('bespoke-pages')}}" class="btn btn-default btn-spacing">Cancel <span>and Return</span></a>
</div>
