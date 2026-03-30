<div class="x_panel tile">
    <div class="x_title">
        <h4>Messages</h4>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div>
            <div class="u-mb1">
                <a class="btn btn-primary" href="{{ admin_url('members/message/'.$user->id) }}">Send New Message</a>
            </div>
            <ul class="list-unstyled top_profiles scroll-view" style="height: auto;">
                @if($messages->count() > 0)
                    @foreach($messages as $message)
                        <li class="media event">
                            <a class="pull-left border-aero profile_thumb">
                                <i class="fa fa-user aero"></i>
                            </a>
                            <div class="media-body">
                                <a class="title" href="#">{{ $message->sender->name }} @if($message->sender->role_id < 4) <strong>(Agent)</strong> @endif</a>
                                <p>{!! nl2br($message->message_content) !!} </p>
                                <p> <small>{{ $message->friendly_date }} at {{ $message->friendly_time }}</small></p>
                            </div>
                        </li>
                    @endforeach
                @else
                    No messages at this time
                @endif
            </ul>
        </div>
    </div>
</div>