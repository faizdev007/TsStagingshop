<div class="x_panel tile">
    <div class="x_title">
        <h4>Shortlist</h4>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div>
            @if($shortlist->count() > 0)
                <ul class="list-unstyled msg_list">
                    @foreach($shortlist as $shortlist)
                        @if(!empty($shortlist->property))
                        <li class="u-relative">
                            <a target="_blank" href="{{ $shortlist->property->url }}">
                                <span class="image">
                                @foreach( $shortlist->property->propertyMediaPhotos->take(1) as $media )
                                        <img src="{{ $media->photo_display }}" alt="{{ $shortlist->property->search_headline }}">
                                @endforeach
                                </span>
                                <span>
                                    <span>{{ $shortlist->property->search_headline }}</span>
                                    <span class="message">{!! $shortlist->property->display_price !!}</span>
                                    <span class="message">{{ $shortlist->property->DisplayPropertyAddress }}</span>
                                    <span class="time">Saved : {{ Carbon\Carbon::parse($shortlist->created_at)->diffForHumans()}}</span>
                                </span>
                            </a>
                        </li>
                        @endif
                    @endforeach
                </ul>
            @else
                <p>Shortlist is empty</p>
            @endif
        </div>
    </div>
</div>
