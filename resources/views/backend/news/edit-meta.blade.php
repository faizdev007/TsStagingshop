<form action="{{ route('news.update_meta', $article->id) }}"
      method="POST">

    @csrf

    @php
        // English meta
        $meta = !empty($article->meta) ? (object) json_decode($article->meta) : null;

        $meta_title       = $meta->title ?? '';
        $meta_keywords    = $meta->keywords ?? '';
        $meta_description = $meta->description ?? '';
    @endphp

    {{-- ================= English ================= --}}
    <div class="x_panel pw-inner-tabs">
        <div class="x_title">
            <h2>English</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>

        <div class="x_content pw-open">
            <div class="xpw-fields">
                <div class="row">

                    <div class="col-md-6">
                        <div class="control-form">
                            <label for="meta_title">Meta Title:</label>
                            <input type="text"
                                   name="meta_title"
                                   id="meta_title"
                                   class="form-control"
                                   placeholder="Please enter..."
                                   value="{{ old('meta_title', $meta_title) }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="control-form">
                            <label for="meta_keywords">Meta Keywords:</label>
                            <input type="text"
                                   name="meta_keywords"
                                   id="meta_keywords"
                                   class="form-control"
                                   placeholder="Please enter..."
                                   value="{{ old('meta_keywords', $meta_keywords) }}">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="control-form">
                            <label for="meta_description">Meta Description:</label>
                            <input type="text"
                                   name="meta_description"
                                   id="meta_description"
                                   class="form-control"
                                   placeholder="Please enter..."
                                   value="{{ old('meta_description', $meta_description) }}">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- ================= Translations ================= --}}
    @if(settings('translations') && isset($languages))

        @php
            $translationMeta = [];

            foreach ($article->translations as $translation) {
                $metaObj = !empty($translation->meta)
                    ? (object) json_decode($translation->meta)
                    : null;

                $translationMeta[$translation->language] = [
                    'title'       => $metaObj->title ?? '',
                    'keywords'    => $metaObj->keywords ?? '',
                    'description' => $metaObj->description ?? '',
                ];
            }
        @endphp

        @foreach($languages as $k => $v)
            <div class="x_panel pw-inner-tabs">
                <div class="x_title">
                    <h2>{{ $k }}</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content pw-open">
                    <div class="xpw-fields">
                        <div class="row">

                            {{-- Meta Title --}}
                            <div class="col-md-6">
                                <div class="control-form">
                                    <label for="meta_title_{{ $v }}">Meta Title:</label>
                                    <input type="text"
                                           name="meta_title_{{ $v }}"
                                           id="meta_title_{{ $v }}"
                                           class="form-control translate-{{ $v }}"
                                           placeholder="Please enter..."
                                           maxlength="500"
                                           value="{{ old('meta_title_'.$v, $translationMeta[$v]['title'] ?? '') }}">
                                </div>
                            </div>

                            {{-- Meta Keywords --}}
                            <div class="col-md-6">
                                <div class="control-form">
                                    <label for="meta_keywords_{{ $v }}">Meta Keywords:</label>
                                    <input type="text"
                                           name="meta_keywords_{{ $v }}"
                                           id="meta_keywords_{{ $v }}"
                                           class="form-control translate-{{ $v }}"
                                           placeholder="Please enter..."
                                           maxlength="500"
                                           value="{{ old('meta_keywords_'.$v, $translationMeta[$v]['keywords'] ?? '') }}">
                                </div>
                            </div>

                            {{-- Meta Description --}}
                            <div class="col-md-12">
                                <div class="control-form">
                                    <label for="meta_description_{{ $v }}">Meta Description:</label>
                                    <input type="text"
                                           name="meta_description_{{ $v }}"
                                           id="meta_description_{{ $v }}"
                                           class="form-control translate-{{ $v }}"
                                           placeholder="Please enter..."
                                           maxlength="500"
                                           value="{{ old('meta_description_'.$v, $translationMeta[$v]['description'] ?? '') }}">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- Ranking UI (unchanged) --}}
            <div id="rankingPotential" class="mb-2 mt-4">
                <h5>Ranking Potential</h5>
                <div class="ranking-meter">
                    <div class="ranking-segment low"><span>Low</span></div>
                    <div class="ranking-segment medium"><span>Medium</span></div>
                    <div class="ranking-segment high"><span>High</span></div>
                    <div class="ranking-pointer"></div>
                </div>
                <div class="ranking-factors mt-2">
                    <div class="ranking-factor-list"></div>
                </div>
            </div>

        @endforeach
    @endif

    {{-- Sticky buttons --}}
    @include('backend.news.sticky-buttons')

</form>