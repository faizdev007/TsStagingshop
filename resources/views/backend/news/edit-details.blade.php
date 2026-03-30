<form action="{{ route('news.update', $article->id) }}"
      method="POST"
      data-toggle="validator">

    @csrf
    @method('PUT')

    {{-- ================= English ================= --}}
    <div class="x_panel pw-inner-tabs">
        <div class="x_title">
            <h2>English</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>

        <div class="x_content pw-open">
            <div class="row">

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="title">Title: {!! required_label() !!}</label>
                        <input type="text"
                               name="title"
                               id="title"
                               class="form-control"
                               placeholder="Please enter..."
                               value="{{ old('title', $article->title) }}">
                        <span class="glyphicon form-control-feedback"></span>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="content">Content: {!! required_label() !!}</label>
                        <textarea name="content"
                                  id="content"
                                  class="mceEditor description"
                                  style="width:100%"
                                  maxlength="60000"
                                  placeholder="Please enter...">{{ old('content', $article->content) }}</textarea>
                        <span class="glyphicon form-control-feedback"></span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ================= Translations ================= --}}
    @if(settings('translations') && isset($languages))

        @php
            $titleTranslations = $article->translations
                ->pluck('title', 'language')
                ->toArray();

            $contentTranslations = $article->translations
                ->pluck('content', 'language')
                ->toArray();
        @endphp

        @foreach($languages as $k => $v)
            <div class="x_panel pw-inner-tabs">
                <div class="x_title">
                    <h2>{{ $k }}</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li>
                            <a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content pw-open">
                    <div class="row">

                        {{-- Title --}}
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Title:</label>
                                <input type="text"
                                       name="title_{{ $v }}"
                                       class="form-control"
                                       placeholder="Please enter..."
                                       value="{{ old('title_'.$v, $titleTranslations[$v] ?? '') }}">
                                <span class="glyphicon form-control-feedback"></span>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Content:</label>
                                <textarea name="content_{{ $v }}"
                                          class="mceEditor description"
                                          style="width:100%"
                                          maxlength="60000"
                                          placeholder="Please enter..."
                                          required>{{ old('content_'.$v, $contentTranslations[$v] ?? '') }}</textarea>
                                <span class="glyphicon form-control-feedback"></span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach
    @endif

    {{-- ================= Meta ================= --}}
    <div class="x_panel pw-inner-tab">
        <div class="row">

            {{-- Status --}}
            <div class="col-md-6">
                <div class="form-group">
                    <label for="status">Status: {!! required_label() !!}</label>

                    <select name="status"
                            id="status"
                            class="form-control select-pw">

                        @if($article->status === 'deleted')
                            <option value="deleted" selected>Archive</option>
                        @else
                            <option value="draft" {{ $article->status === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ $article->status === 'published' ? 'selected' : '' }}>Published</option>
                        @endif

                    </select>

                    <span class="glyphicon form-control-feedback"></span>
                </div>
            </div>

            {{-- Date Published --}}
            <div class="col-md-6">
                <div class="form-group">
                    <label for="date_published">Date Published: {!! required_label() !!}</label>
                    <input type="text"
                           name="date_published"
                           id="date_published"
                           class="form-control datepicker"
                           placeholder="Please enter..."
                           value="{{ old('date_published', $article->date_published) }}">
                    <span class="glyphicon form-control-feedback"></span>
                </div>
            </div>

            {{-- Categories --}}
            <div class="col-md-12">
                <div class="form-group">
                    <label for="id-tags">Categories</label>
                    <select id="id-tags"
                            name="location_tags[]"
                            class="form-control select-pw">
                        <option value="">Please enter...</option>

                        @foreach($postTags as $id => $tag)
                            <option value="{{ $id }}"
                                {{ in_array($tag, $postTagsSelected) ? 'selected' : '' }}>
                                {{ ucwords($tag) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>

        {{-- Sticky buttons --}}
        @include('backend.news.sticky-buttons')
    </div>

</form>