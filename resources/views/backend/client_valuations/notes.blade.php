<div class="x_panel pw-inner-tabs">
    <div class="x_content">
        <div class="xpw-fields">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <legend>Notes</legend>
                </div>
            </div>
            @if($data->notes->count() > 0)
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="list-unstyled msg_list">
                            @foreach($data->notes as $note)
                                <li>
                                    <a>
                                    <span>
                                        <span class="note-title">{{ $note->client_valuation_note_title }} - {{ date("jS F Y", strtotime($note->created_at)) }} at {{ date("g:ia", strtotime($note->created_at)) }}</span>
                                    </span>
                                    <span class="note-message">
                                    {!! $note->client_valuation_text !!}
                                        <div class="u-block u-mt1">
                                            @if($note->client_valuation_note_type == 'internal')
                                                <span class="badge">Internal Note</span>
                                            @else
                                                <span class="badge">Customer Note</span>
                                            @endif
                                        </div>
                                    </span>
                                    </a>
                                </li>
                                <div class="ln_solid"></div>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <p>No notes created.</p>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-lg-12">
                    <a class="btn btn-primary" href="{{ admin_url('market-valuation/note/'.$data->client_valuation_id) }}">Create new Note</a>
                </div>
            </div>
        </div>
    </div>
</div>
