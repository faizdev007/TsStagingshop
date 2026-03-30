<a href="{{admin_url('enquiries')}}" class="btn btn-default btn-spacing">Cancel <span>and Return</span></a>
@if($enquiry->archived_at != NULL)
    <a href="{{admin_url('enquiries/'.$enquiry->id.'/reactivate')}}" class="confirm-action btn btn-info" title="reactivate this lead">Reactivate</a>
@else
    <a href="#" class="btn btn-danger btn-spacing modal-toggle"
       title="delete this lead"
       data-item-id="{{ $enquiry->id }}"
       data-toggle="modal"
       data-modal-type="delete"
       data-modal-title="Delete Enquiry"
       data-modal-size="small"
       data-delete-type="enquiries"
       data-target="#global-modal"
    > <i class="fas fa-trash"></i> Delete</a>
@endif
