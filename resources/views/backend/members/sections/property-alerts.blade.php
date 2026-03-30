<div class="x_panel tile">
    <div class="x_title">
        <h4>Property Alerts</h4>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div>
            @if($alerts->count() > 0)
                <ul class="list-unstyled msg_list">
                    @foreach($alerts as $alert)
                        <li class="u-relative">
                            <a>
                                <span>
                                    <span>{!! $alert->detailsHeadline !!}</span>
                                    <span class="time">Saved : {{ Carbon\Carbon::parse($alert->created_at)->diffForHumans()}}</span>
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>Nothing saved</p>
            @endif
        </div>
    </div>
</div>
