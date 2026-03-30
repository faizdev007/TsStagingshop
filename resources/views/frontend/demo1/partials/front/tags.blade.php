<div class="recent-box">
    <h3>Tags</h3>
    <div class="blog--tags">
        <?php
        $tags = \App\Tag::take(25)->get();
        $rnd = array('<span>#link#</span>', '#link#');
        $count = 1;
        ?>
        @foreach($tags as $tg)
        <?php
        $link = HTMl::link($tg->getUrl(), $tg->name);
        ?>
        @if(($count % 2) == 0)
            {!! str_replace('#link#', $link, $rnd[0]) !!}
        @else
            {!! str_replace('#link#', $link, $rnd[1]) !!}
        @endif
        <?php $count++;?>
        @endforeach
    </div>
</div>
