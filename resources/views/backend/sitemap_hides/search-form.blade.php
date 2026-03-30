<form action="{{ route('sitemap_hides.index') }}"
      method="GET"
      class="search-form">

    <ul class="nav navbar-right panel_toolbox">
        <li class="top-button">
            <a href="{{ admin_url('sitemap_hides/create') }}"
               class="btn btn-small btn-primary">
                Add URL
            </a>
        </li>
    </ul>

    <ul class="sf-field">

        {{-- Keyword --}}
        <li>
            <input type="text"
                   name="q"
                   class="form-control"
                   placeholder="Keyword..."
                   value="{{ request('q') }}">
        </li>

        {{-- Actions --}}
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
