<form
    action="{{ route('pages.update', $page->id) }}"
    method="POST"
    data-toggle="validator"
>
    @csrf
    @method('PUT')

    {{-- English --}}
    <div class="x_panel pw-inner-tabs">
        <div class="x_title">
            <h2>English</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>

        <div class="x_content pw-open">
            <div class="row">

                <div class="col-md-6">
                    <div class="control-form">
                        <label>Title: {!! required_label() !!}</label>
                        <input
                            type="text"
                            name="title"
                            id="location"
                            class="form-control get-slug"
                            data-type="pages"
                            placeholder="Please enter..."
                            required
                            value="{{ old('title', $page->title) }}"
                        >
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="control-form">
                        <label>Sub Title:</label>
                        <input
                            type="text"
                            name="header_title"
                            class="form-control"
                            placeholder="Please enter..."
                            value="{{ old('header_title', $page->header_title) }}"
                        >
                    </div>
                </div>

                @if(settings('bespoke_pages') == 1)
                    <div class="col-md-6">
                        <div class="control-form">
                            <label>Page Slug (URL): {!! required_label() !!}</label>
                            <input
                                type="text"
                                name="route"
                                class="form-control slug-return"
                                placeholder="Page Slug (URL)"
                                required
                                value="{{ old('route', $page->route) }}"
                            >
                        </div>
                    </div>
                @endif

                <div class="col-md-12">
                    <div class="control-form">
                        <label>Content: {!! required_label() !!}</label>
                        <textarea
                            name="content"
                            id="content"
                            class="mceEditor description"
                            style="width:100%"
                            placeholder="Please enter..."
                            maxlength="60000"
                        >{{ old('content', $page->content) }}</textarea>
                    </div>
                </div>

                {{-- Home page --}}
                @if($page->route === '/')
                    <div class="x_title">
                        <h2>Read More Section</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <div class="col-md-12">
                        <div class="control-form">
                            <label>Content: {!! required_label() !!}</label>
                            <textarea
                                name="content1"
                                id="content1"
                                class="mceEditor description"
                                style="width:100%"
                                placeholder="Please enter..."
                                maxlength="60000"
                            >{{ old('content1', $page->content1) }}</textarea>
                        </div>
                    </div>
                @endif

                {{-- About page --}}
                @if($page->route === 'about-us')
                    <div class="col-md-12">
                        <div class="control-form">
                            <label>Quote</label>
                            <textarea
                                name="content1"
                                id="content1"
                                class="mceEditor description"
                                style="width:100%"
                                placeholder="Please enter..."
                                maxlength="60000"
                            >{{ old('content1', $page->content1) }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <h2>CEO's Bio</h2>

                        <div class="control-form">
                            <label>Heading:</label>
                            <input
                                type="text"
                                name="heading1"
                                class="form-control"
                                placeholder="Please enter..."
                                value="{{ old('heading1', $page->heading1) }}"
                            >
                        </div>

                        <div class="control-form">
                            <label>Heading 1:</label>
                            <input
                                type="text"
                                name="heading2"
                                class="form-control"
                                placeholder="Please enter..."
                                value="{{ old('heading2', $page->heading2) }}"
                            >
                        </div>

                        <div class="control-form">
                            <label>Content:</label>
                            <textarea
                                name="content2"
                                class="mceEditor description"
                                style="width:100%"
                                placeholder="Please enter..."
                                maxlength="60000"
                            >{{ old('content2', $page->content2) }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <h2>DISCOVER OUR PROPERTY</h2>
                        <div class="control-form">
                            <label>Content:</label>
                            <textarea
                                name="content3"
                                id="content3"
                                class="mceEditor description"
                                style="width:100%"
                                placeholder="Please enter..."
                                maxlength="60000"
                            >{{ old('content3', $page->content3) }}</textarea>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Translations --}}
    @if(settings('translations') && isset($languages))
        @php
            $translations = [];
            foreach ($page->translations as $t) {
                $translations[$t->language] = $t;
            }
        @endphp

        @foreach($languages as $label => $lang)
            <div class="x_panel pw-inner-tabs">
                <div class="x_title">
                    <h2>{{ $label }}</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content pw-open">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Title:</label>
                                <input
                                    type="text"
                                    name="title_{{ $lang }}"
                                    class="form-control"
                                    placeholder="Please enter..."
                                    value="{{ old('title_'.$lang, $translations[$lang]->title ?? '') }}"
                                >
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Content:</label>
                                <textarea
                                    name="content_{{ $lang }}"
                                    class="mceEditor description"
                                    style="width:100%"
                                    placeholder="Please enter..."
                                    maxlength="60000"
                                    required
                                >{{ old('content_'.$lang, $translations[$lang]->content ?? '') }}</textarea>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach
    @endif

    @include('backend.pages.sticky-buttons')
</form>