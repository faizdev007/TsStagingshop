<form action="{{admin_url('slides/'.$slide->id)}}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="xpw-fields">
        <div class="row">

            <div class="col-md-6">
                <div class="control-form">
                    <label for="fullname">Tagline 1: {!! required_label() !!}</label>
                    <input type="text" id="text_line1" value="{{$slide->text_line1}}" class="form-control" name="text_line1" placeholder="Please enter..." />
                </div>
            </div>

            <div class="col-md-6">
                <div class="control-form">
                    <label for="fullname">Tagline 2:</label>
                    <input type="text" id="text_line2" value="{{$slide->text_line2}}" class="form-control" name="text_line2" placeholder="Please enter..." />
                </div>
            </div>

            @if($slide->type == "video")
                <div class="col-md-5">
                    <div class="control-form">
                        <label for="fullname">Video Source:</label>
                        <select name="source" class="form-control select-pw" data-placeholder="Video Source">
                            <option></option>
                            <option @if($slide->source == "youtube") selected="selected") @endif value="youtube">YouTube</option>
                            <option @if($slide->source == "vimeo") selected="selected") @endif value="vimeo">Vimeo</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="control-form">
                        <label for="fullname">Video ID:</label>
                        <input type="text" id="video_id" value="{{$slide->video_id}}" class="form-control" name="video_id" placeholder="Please enter..." />
                        <small class="form-text text-muted">e.g YouTube https://www.youtube.com/watch?v=</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="control-form">
                        <label>&nbsp;</label>
                        <div class="form-check">
                            <input name="show_text" class="form-check-input" type="checkbox" @if($slide->show_text) checked="checked" @endif value="1" id="bannerText">
                            <label class="form-check-label" for="bannerText">
                                Show Banner Text
                            </label>
                        </div>
                    </div>
                </div>
            @endif

        </div>

        <input type="hidden" name="type" value="{{ $slide->type }}">

        <div class="form-group sticky-buttons">
            <button type="submit" class="btn btn-large btn-primary" >Save</button>
            <a href="{{admin_url('slides')}}" class="btn btn-default btn-spacing">Cancel <span>and Return</span></a>
            <a href="#" class="btn btn-danger btn-spacing modal-toggle"
               data-item-id="{{ $slide->id }}"
               data-toggle="modal"
               data-modal-type="delete"
               data-modal-title="Delete Slide"
               data-modal-size="small"
               data-delete-type="slides"
               data-target="#global-modal"
            ><i class="fas fa-trash"></i> Delete</a>
        </div>

        @if(settings('translations'))
            @if(isset($languages))
                @php $count = 0; @endphp
                @foreach($languages as $k => $v)
                    <div class="xpw-fields">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>{{ $k }}</h3>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            $translations = $slide->translations;
                            $saved_translations = array();
                            $line1 = array();
                            $line2 = array();
                            foreach($translations as $translation)
                            {
                                $saved_translations[] = $translation->language;
                                $line1[$translation->language] = $translation->text_line1;
                                $line2[$translation->language] = $translation->text_line2;
                            }
                            ?>
                            <div class="col-md-6">
                                <div class="control-form">
                                    <label for="fullname">Text Line 1: {!! required_label() !!}</label>
                                    @if(in_array($v, $saved_translations))
                                        <input type="text" value="{{ $line1[$v] }}" id="text_line1_{{ $v }}" class="form-control translate-{{$v}}" name="text_line1_{{ $v }}" placeholder="Please enter..." />
                                    @else
                                        <input type="text" value="" id="text_line1_{{ $v }}" class="form-control translate-{{$v}}" name="text_line1_{{ $v }}" placeholder="Please enter..." />
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="control-form">
                                    <label for="fullname">Text Line 2:</label>
                                    @if(in_array($v, $saved_translations))
                                        <input type="text" value="{{ $line2[$v] }}" id="text_line2_{{ $v }}" class="form-control translate-{{$v}}" name="text_line2_{{ $v }}" placeholder="Please enter..." />
                                    @else
                                        <input type="text" value="" id="text_line2_{{ $v }}" class="form-control translate-{{$v}}" name="text_line2_{{ $v }}" placeholder="Please enter..." />
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @php $count++ @endphp
                @endforeach
            @endif
        @endif

    </div>

</form>
