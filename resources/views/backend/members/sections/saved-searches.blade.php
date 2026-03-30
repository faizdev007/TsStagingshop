<div class="x_panel tile">
    <div class="x_title">
        <h4>Saved Searches</h4>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div>
            @if($searches->count() > 0)
                <ul class="list-unstyled msg_list">
                    @foreach($searches as $search)
                        @if(!empty($search))
                        <li class="u-relative">
                            <a href="{{ url($search->saved_search_url) }}" target="_blank">
                                <span>
                                    <span>{{ ucfirst($search->searchHeadline) }}</span>
                                    <span class="time">Saved : {{ Carbon\Carbon::parse($search->created_at)->diffForHumans()}}</span>
                                </span>
                            </a>
                        </li>@endif
                    @endforeach
                </ul>
            @else
                <p>Nothing saved</p>
            @endif
        </div>
    </div>
</div>
