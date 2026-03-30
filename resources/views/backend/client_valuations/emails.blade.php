<div class="x_panel pw-inner-tabs">
    <div class="x_content">
        <div class="xpw-fields">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <legend>Emails</legend>
        </div>
    </div>

    @if($data->emails->count() > 0)
        <table class="table table-striped jambo_table bulk_action table-bordered-">
            <thead>
            <tr>
                <th>Subject</th>
                <th>Opens</th>
                <th>Link Clicks</th>
                <th>Date Sent</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data->emails as $message)
                <tr>
                    <td>{{ $message->email->subject }}</td>
                    <td>{{ $message->email->opens }}</td>
                    <td>
                        @if($message->email->clicks >= 1)
                            <a class="modal-toggle"
                               href="#"
                               data-item-id="{{ $message->email->id }}"
                               data-toggle="modal"
                               data-modal-type="data"
                               data-modal-title="Email Clicks"
                               data-data-type="email-clicks"
                               data-target="#global-modal"
                            >
                                {{ $message->email->clicks }}
                            </a>
                        @else
                            0
                        @endif
                    </td>
                    <td>{{ date("jS F Y", strtotime($message->email->created_at)) }} at {{ date("g:ia", strtotime($message->email->created_at)) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

    @if($data->client_valuation_status != 'instructed')
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <a href="#" class="btn btn-primary modal-toggle"
                   data-toggle="modal"
                   data-modal-type="email"
                   data-item-id="{{ $data->client_valuation_id}}"
                   data-modal-title="Send Valuation"
                   data-modal-size="small"
                   data-send-type="market-valuation-email"
                   data-target="#global-modal"
                >Send Valuation</a>
            </div>
        </div>
    @endif
</div>
    </div>
</div>