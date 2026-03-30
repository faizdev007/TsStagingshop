<a href="{{admin_url('properties')}}" class="btn btn-default btn-spacing">Cancel <span>and Return</span></a>
@if($property->status == 1)
<a href="{{admin_url('properties/'.$property->id.'/reactive')}}" class="confirm-action btn btn-large btn-info btn-spacing" title="reactivate this property">Reactivate</a>
<a href="#" class="btn btn-danger btn-spacing modal-toggle"
   data-item-id="{{ $property->id }}"
   data-toggle="modal"
   data-modal-type="delete"
   data-modal-size="small"
   data-target="#global-modal"
   data-delete-type="properties"
   data-modal-title="Delete this property">
    <i class="far fa-trash-alt"></i> Delete
</a>
@else
@if($property->archived_at)
    <a href="{{admin_url('properties/'.$property->id.'/reactive')}}" class="btn btn-info btn-spacing modal-toggle">Reactivate this property</a>
@else
<a href="{{admin_url('properties/'.$property->id.'/archive')}}" class="btn btn-danger btn-spacing confirm-action"><i class="far fa-trash-alt"></i> Archive</a>
@endif
@endif
