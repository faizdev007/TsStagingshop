<form
    action="{{ route('pages.update_meta', $page->id) }}"
    method="POST"
>
    @csrf

    @php
        // Pull metadata (English)
        $meta = !empty($page->meta) ? (object) json_decode($page->meta) : null;

        $meta_title = $meta->title ?? '';
        $meta_description = $meta->description ?? '';
        $meta_keywords = $meta->keywords ?? '';
    @endphp

    <input type="hidden" name="segment" value="{{ request()->segment(2) }}">

    {{-- English --}}
    <div class="x_panel pw-inner-tabs">
        <div class="x_title">
            <h2>English</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="collapse-link">
                        <i class="fa fa-chevron-down"></i>
                    </a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>

        <div class="x_content pw-open">
            <div class="xpw-fields">
                <div class="row">

                    <div class="col-md-6">
                        <div class="control-form">
                            <label for="meta_title">Meta Title:</label>
                            <input
                                type="text"
                                name="meta_title"
                                id="meta_title"
                                class="form-control"
                                placeholder="Please enter..."
                                value="{{ old('meta_title', $meta_title) }}"
                            >
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="control-form">
                            <label for="meta_description">Meta Description:</label>
                            <input
                                type="text"
                                name="meta_description"
                                id="meta_description"
                                class="form-control"
                                placeholder="Please enter..."
                                value="{{ old('meta_description', $meta_description) }}"
                            >
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="control-form">
                            <label for="meta_keywords">Meta Keywords:</label>
                            <input
                                type="text"
                                name="meta_keywords"
                                id="meta_keywords"
                                class="form-control"
                                placeholder="Please enter..."
                                value="{{ old('meta_keywords', $meta_keywords) }}"
                            >
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Translations --}}
    @if(settings('translations') && isset($languages))
        @php
            // Prepare translation meta lookup
            $translationMeta = [];

            foreach ($page->translations as $translation) {
                $decoded = !empty($translation->meta)
                    ? (object) json_decode($translation->meta)
                    : null;

                $translationMeta[$translation->language] = [
                    'title'       => $decoded->title ?? '',
                    'keywords'    => $decoded->keywords ?? '',
                    'description' => $decoded->description ?? '',
                ];
            }
        @endphp

        @foreach($languages as $label => $lang)
            <div class="x_panel pw-inner-tabs">
                <div class="x_title">
                    <h2>{{ $label }}</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li>
                            <a class="collapse-link">
                                <i class="fa fa-chevron-down"></i>
                            </a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content pw-open">
                    <div class="xpw-fields">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="control-form">
                                    <label for="meta_title_{{ $lang }}">Meta Title:</label>
                                    <input
                                        type="text"
                                        name="meta_title_{{ $lang }}"
                                        id="meta_title_{{ $lang }}"
                                        class="form-control translate-{{ $lang }}"
                                        placeholder="Please enter..."
                                        maxlength="500"
                                        value="{{ old('meta_title_'.$lang, $translationMeta[$lang]['title'] ?? '') }}"
                                    >
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="control-form">
                                    <label for="meta_keywords_{{ $lang }}">Meta Keywords:</label>
                                    <input
                                        type="text"
                                        name="meta_keywords_{{ $lang }}"
                                        id="meta_keywords_{{ $lang }}"
                                        class="form-control translate-{{ $lang }}"
                                        placeholder="Please enter..."
                                        maxlength="500"
                                        value="{{ old('meta_keywords_'.$lang, $translationMeta[$lang]['keywords'] ?? '') }}"
                                    >
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="control-form">
                                    <label for="meta_description_{{ $lang }}">Meta Description:</label>
                                    <input
                                        type="text"
                                        name="meta_description_{{ $lang }}"
                                        id="meta_description_{{ $lang }}"
                                        class="form-control translate-{{ $lang }}"
                                        placeholder="Please enter..."
                                        maxlength="500"
                                        value="{{ old('meta_description_'.$lang, $translationMeta[$lang]['description'] ?? '') }}"
                                    >
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    @include('backend.pages.sticky-buttons')
</form>