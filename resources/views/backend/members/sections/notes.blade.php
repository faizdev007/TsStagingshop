<div class="x_panel tile">
    <div class="x_title">
        <h4>Notes</h4>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div>
            @if($notes->count() > 0)
                <ul class="list-unstyled msg_list">
                    @foreach($notes as $note)
                        <li class="u-relative">
                            <a target="_blank" href="{{ $note->property->url }}">
                                <span class="image">
                                    @foreach( $note->property->propertyMediaPhotos->take(1) as $media )
                                        <img src="{{ $media->photo_display }}" alt="{{ $note->property->search_headline }}">
                                    @endforeach
                               </span>
                               <span>
                               <span>
                                <span>{{ $note->property->search_headline }}</span>
                                <span class="message">{{ $note->note_content }}</span>
                                <span class="time">Saved : {{ Carbon\Carbon::parse($note->created_at)->diffForHumans()}}</span>
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
