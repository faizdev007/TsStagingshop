@if(settings('footer_blocks') == 1)
    <section class="footer u-pt0 u-pb0">
        <div class="footer-content">
            <div class="container-footer">
                    <div class="row">
                        <?php
                            $num_blocks = $footer_blocks->count();
                            $num_columns = 12 / $num_blocks;
                        ?>
                        @foreach($footer_blocks as $footer_block)
                            <div class="col-xs-12 col-md-{{$num_columns}}">
                                <h4 class="f-18 f-two f-bold u-block u-mb1">{{ $footer_block->footer_block_title }}</h4>
                                @if(!$footer_block->links->isEmpty())
                                    <div class="footer-nav footer-block">
                                        <ul>
                                            @foreach($footer_block->links as $link)
                                                <li>
                                                    <a @if($link->footer_link_type == 'custom-link') target="_blank" @endif href="{{ $link->footer_link_url }}">{{ $link->footer_link_title }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div><!-- /.footer-nav -->
                                @endif
                            </div><!-- /.col-xs-12 -->
                       @endforeach
                    </div><!-- /.row -->
            </div><!-- /.container -->
        </div><!-- /.footer-content -->
    </section>
@endif