<div class="form-group sticky-buttons">
    <button type="submit" class="btn btn-large btn-primary" >Save</button>
    <a href="{{admin_url('news')}}" class="btn btn-default btn-spacing">Cancel <span>and Return</span></a>
    @if($article->status == 'deleted')
        <a href="{{admin_url('news/'.$article->id.'/restore')}}" class="confirm-action btn btn-small btn-success" title="restore this article">Restore</a>
    @else
        <a href="#" class="btn btn-danger btn-spacing modal-toggle"
           data-item-id="{{ $article->id }}"
           data-toggle="modal"
           data-modal-type="delete"
           data-modal-title="Delete Article"
           data-modal-size="small"
           data-delete-type="news"
           data-target="#global-modal"
        ><i class="fas fa-trash"></i> Delete </a>
    @endif
</div>
