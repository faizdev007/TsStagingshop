<form action="{{ route('news.index') }}"
      method="GET"
      class="search-form">

    <ul class="nav navbar-right panel_toolbox">
        <li class="top-button">
            <a href="{{ admin_url('news/create') }}"
               class="btn btn-small btn-primary">
                Add Article
            </a>
        </li>
    </ul>

    <ul class="sf-field">

        {{-- Status --}}
        <li class="medium">
            <select name="status"
                    id="status"
                    class="form-control select-pw">
                <option value="">Status</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
            </select>
        </li>

        {{-- Categories --}}
        @if($categories->count())
            <li>
                <select name="category" class="select-pw">
                    <option value="">Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </li>
        @endif

        {{-- Keyword --}}
        <li>
            <input type="text"
                   name="q"
                   class="form-control"
                   placeholder="Keyword..."
                   value="{{ request('q') }}">
        </li>

        {{-- Buttons --}}
        <li>
            <div class="pw-search-btn">
                <div class="psb-col">
                    <button type="submit"
                            name="search"
                            value="yes"
                            class="btn btn-small btn-primary pw-search-btn">
                        Search
                    </button>
                </div>

                <div class="psb-col">
                    <a href="{{ url()->current() }}"
                       class="btn btn-small btn-default pw-search-btn">
                        Clear <span>Search</span>
                    </a>
                </div>
            </div>
        </li>

    </ul>
</form>