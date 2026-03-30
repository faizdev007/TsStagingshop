<div class="x_panel tile">
    <div class="x_title">
        <h4>Automated Messages</h4>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div>
            @if($automations->count() > 0)
                @foreach($automations as $automation)
                    @if(!empty($automation))
                        <h4>{{ ucfirst($automation->lead_type) }}</h4>
                        @if($automation->messages->count() > 0 )
                            <table class="table table-striped jambo_table bulk_action table-bordered-">
                                <thead>
                                <tr>
                                    <th>Message Type</th>
                                    <th>Subject</th>
                                    <th>Opens</th>
                                    <th>Link Clicks</th>
                                    <th>Date Sent</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($automation->messages as $message)
                                    <tr>
                                        <td>{{ ucfirst($automation->lead_type) }}</td>
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
                                        <td>{{ $message->email->friendly_date }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                        @if($automation->lead_is_subscribed == 'y')
                            <p>Next {{ ucfirst($automation->lead_type) }} message scheduled for <strong>{{ $automation->next_message }}</strong></p>
                        @else
                            @if(!$automation->messages->first())
                                <p>First {{ ucfirst($automation->lead_type) }} message scheduled for <strong>{{ $automation->next_message }}</strong></p>
                            @endif
                        @endif
                        @if($automation->lead_is_subscribed == 'y')
                            <a href="{{admin_url('enquiries/remove-automation/'.$automation->lead->id.'')}}" class="btn btn-small btn-primary">Opt Out of {{ ucfirst($automation->lead_type) }} Emails</a>
                        @else
                            <strong>Opted out on : {{ $automation->last_updated }}</strong>
                        @endif
                    @endif
                @endforeach
                @else
                <p>No automations at this time</p>
            @endif
        </div>
    </div>
</div>