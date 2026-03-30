        <form action="{{admin_url('sitemap_hides/'.$item->id)}}" method="POST">
            @csrf
            @method('PUT')
            <div class="xpw-fields">
                <div class="row">

                    <div class="col-md-6">
                        <div class="control-form">
                            <label for="url">URL: {!! required_label() !!}</label>
                            <input type="text" id="url" value="{{$item->url}}" class="form-control" name="url" placeholder="Please enter..." />
                        </div>
                    </div>

                </div>

                <div class="form-group sticky-buttons">
                    <button type="submit" class="btn btn-large btn-primary" >Save</button>
                    <a href="{{admin_url('sitemap_hides')}}" class="btn btn-default btn-spacing">Cancel <span>and Return</span></a>
                    <a href="#" class="btn btn-danger btn-spacing modal-toggle"
                       data-item-id="{{ $item->id }}"
                       data-toggle="modal"
                       data-modal-type="delete"
                       data-modal-title="Delete URL"
                       data-modal-size="small"
                       data-delete-type="sitemap_hides"
                       data-target="#global-modal"
                        ><i class="fas fa-trash"></i> Delete</a>
                </div>

            </div>

        </form>
