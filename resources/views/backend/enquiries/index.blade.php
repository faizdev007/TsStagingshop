@extends('backend.layouts.master')

@section('admin-content')


<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel pw">
            <div class="x_title">
                <div class="search-form-style-1">
                    @include('backend.enquiries.search-form')
                </div>
            </div>
            <div class="x_content">
                <div class="pw-table">
                    <form
                        action="{{ route('enquiries.selection-note-update') }}"
                        method="POST"
                        id="multiselectfrom"
                    >
                        @csrf

                        <div id="multioption" class="hidden" style="display: flex; justify-content: space-between;">
                            <input type="hidden" id="m_states" name="m_states"/>

                            <div>
                                <button type="button" onclick="changeStatus('Inactive')" class="btn border-dark" title="Inactive">
                                    <i class="fa fa-user-slash"></i>
                                </button>

                                <button type="button" onclick="changeStatus('Hot')" class="btn border-dark" title="Hot">
                                    <i class="fa fa-bolt"></i>
                                </button>

                                <button type="button" onclick="changeStatus('Normal')" class="btn border-dark" title="Normal">
                                    <i class="fa fa-circle"></i>
                                </button>

                                <button type="button" onclick="changeStatus('Close')" class="btn border-dark" title="Close">
                                    <i class="fa fa-window-close"></i>
                                </button>

                                <button type="button" onclick="changeStatus('Scam')" class="btn border-dark" title="Scam">
                                    <i class="fa fa-exclamation-circle"></i>
                                </button>
                            </div>

                            <!-- Add Note Modal Trigger -->
                            <button
                                type="button"
                                class="btn border-dark"
                                data-toggle="modal"
                                data-target="#exampleModal"
                                aria-label="Add Note"
                                title="Add Note"
                            >
                                <i class="fa fa-sticky-note"></i>
                            </button>
                        </div>

                        @if(!empty($enquiries->count()))
                            <div class="scroll table-responsive ">
                                <table class="table table-striped table-bordered text-nowrap jambo_table bulk_action">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div style="display:flex; align-items:center; position:relative;">
                                                    <input
                                                        type="checkbox"
                                                        id="selectAll"
                                                        style="position:absolute; margin:0 5px;"
                                                    >

                                                    <select
                                                        id="gm_select"
                                                        style="margin:0; color:black; width:33px;"
                                                    >
                                                        @foreach(gm_select() as $key => $label)
                                                            <option value="{{ $key }}">{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Telephone</th>
                                            <th>Category</th>

                                            @if(settings('branches_option') == 1 && Auth::user()->role_id <= '2')
                                                <th>Branch</th>
                                            @endif

                                            <th>Date Submitted</th>
                                            <th>Last Updated Date</th>
                                            <th>Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody class="selectionArea">
                                        @foreach($enquiries as $enquiry)
                                            @if(!in_array($enquiry->email, [
                                                'max@talosgrowth.nl',
                                                'ericjonesmyemail@gmail.com',
                                                'mike@monkeydigital.co',
                                                'yawiviseya67@gmail.com',
                                                'cheeck-tttt@gmail.com',
                                                'mikexxxx@gmail.com',
                                                'ebojajuje04@gmail.com',
                                                'check-message2511@gmail.com',
                                                'info@speed-seo.net',
                                                'info@digital-x-press.com',
                                                'info@professionalseocleanup.com'
                                            ]))
                                                <tr id="item-{{ $enquiry->id }}">
                                                    <td>
                                                        <input
                                                            type="checkbox"
                                                            name="es[]"
                                                            value="{{ $enquiry->id }}"
                                                            cStatus="{{ $enquiry->e_status ?? '--' }}"
                                                        >
                                                    </td>

                                                    <td>
                                                        {{ strip_tags($enquiry->name ?? '--') }}

                                                        @if(Str::startsWith($enquiry->category, 'Rightmove'))
                                                            <span title="{{ $enquiry->category }}" class="brand-icon -rightmove"></span>
                                                        @endif

                                                        @if(Str::startsWith($enquiry->category, 'Zoopla'))
                                                            <span title="{{ $enquiry->category }}" class="brand-icon -zoopla"></span>
                                                        @endif

                                                        @if(empty($enquiry->read_at))
                                                            <br>
                                                            <span class="label label-success">New</span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <a href="mailto:{{ $enquiry->email ?? '#' }}">
                                                            {{ strip_tags($enquiry->email ?? '--') }}
                                                        </a>
                                                    </td>

                                                    <td>
                                                        <a href="tel:{{ $enquiry->telephone ?? '#' }}">
                                                            {{ strip_tags($enquiry->telephone ?? '--') }}
                                                        </a>
                                                    </td>

                                                    <td>
                                                        @if(!empty($enquiry->property->ref))
                                                            {{ $enquiry->property->ref }} - {{ $enquiry->category }}
                                                        @else
                                                            {{ $enquiry->category }}
                                                        @endif
                                                    </td>

                                                    @if(settings('branches_option') == 1 && Auth::user()->role_id <= '2')
                                                        <td>
                                                            {{ $enquiry->branch->branch_name ?? 'N/A' }}
                                                        </td>
                                                    @endif

                                                    <td>{{ $enquiry->display_date }}</td>
                                                    <td>{{ admin_date($enquiry->updated_at) }}</td>
                                                    <td>{{ $enquiry->e_status ?? '--' }}</td>

                                                    <td class="text-center table-active-btn">
                                                        <a
                                                            href="{{ admin_url('enquiries/'.$enquiry->id.'/edit') }}"
                                                            class="btn btn-small {{ empty($enquiry->reply_message) ? 'btn-primary' : 'btn-info' }}"
                                                        >
                                                            {{ empty($enquiry->reply_message) ? 'Action' : 'Review' }}
                                                        </a>

                                                        @if($enquiry->archived_at)
                                                            |
                                                            <a
                                                                href="{{ admin_url('enquiries/'.$enquiry->id.'/reactivate') }}"
                                                                class="confirm-action btn btn-small btn-info"
                                                            >
                                                                Reactivate
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                            <div style="margin-top: 15px;">
                                {{ $enquiries->links('pagination::bootstrap-4') }}
                            </div>
                        @else
                            <div class="no-data">No data found.</div>
                        @endif

                        <!-- Notes Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"><label>Your Notes</label></h5>
                                        <button
                                            type="button"
                                            class="close"
                                            data-dismiss="modal"
                                            onclick="tinymce.get('add_notes').setContent('');"
                                        >
                                            &times;
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <textarea
                                            name="add_notes"
                                            id="add_notes"
                                            class="mceEditor description"
                                            placeholder="Please enter..."
                                            maxlength="60000"
                                        ></textarea>
                                    </div>

                                    <div class="modal-footer">
                                        <button
                                            type="button"
                                            class="btn btn-secondary"
                                            data-dismiss="modal"
                                            onclick="tinymce.get('add_notes').setContent('');"
                                        >
                                            Close
                                        </button>

                                        <button
                                            type="button"
                                            id="savenotemessage"
                                            data-dismiss="modal"
                                            class="btn btn-primary"
                                        >
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('headerscripts')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('footerscripts')
<script src="{{asset('assets/admin/build/vendors/jquery/jquery.jscroll.min.js')}}"></script>
<script src="{{asset('assets/admin/build/js/pw-lazy-pagination.js')}}"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {

    const selectallCheck   = document.getElementById('selectAll');
    const selectionAreaMain = document.getElementsByClassName('selectionArea');
    const selectAction     = document.getElementById('selectAction');
    const multiselectfrom  = document.getElementById('multiselectfrom');
    const actionblock      = document.getElementById('multioption');
    const selectopt        = document.getElementById('gm_select');
    
    // Helper => get all checkboxes inside all selection areas
    const getAllCheckboxes = () => 
        document.querySelectorAll('.selectionArea input[type="checkbox"]');

    // Helper => toggle action buttons
    const toggleActionBlock = () => {
        const anyChecked = [...getAllCheckboxes()].some(x => x.checked);
        actionblock.classList.toggle('hidden', !anyChecked);
        selectallCheck.checked = anyChecked;
    };

    // Handle dropdown selection (filter logic)
    selectopt.addEventListener('change', () => {
        const value = selectopt.value;
        const checkboxes = getAllCheckboxes();

        checkboxes.forEach(cb => {
            const status = cb.getAttribute('cStatus');

            if (value === 'all') {
                cb.checked = true;
            } else if (value === '') {
                cb.checked = false;
            } else {
                cb.checked = (status === value);
            }
        });

        toggleActionBlock();
    });


    // Handle Select All checkbox
    selectallCheck.addEventListener('change', () => {
        const checkboxes = getAllCheckboxes();
        const check = selectallCheck.checked;

        checkboxes.forEach(cb => cb.checked = check);
        toggleActionBlock();
    });


    // Monitor manual checkbox clicks
    getAllCheckboxes().forEach(cb => {
        cb.addEventListener('change', toggleActionBlock);
    });
    
});

function changeStatus(status) {
    if (confirm("You are about to change the status to " + status)) {
        document.getElementById('m_states').value = status;
        document.getElementById('multiselectfrom').submit();
    }
}
</script>
@endpush

