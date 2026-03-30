<form
    action="{{ route('subscribers.index') }}"
    method="GET"
    class="search-form"
>

    <ul class="nav navbar-right panel_toolbox">
        <li class="top-button">
            <a
                href="{{ admin_url('subscribers/csv/download') }}"
                class="btn btn-small btn-primary"
            >
                Download CSV
            </a>
        </li>
    </ul>

    <ul class="sf-field">
        <li>
            <input
                type="text"
                name="q"
                value="{{ $request->input('q') }}"
                class="form-control"
                placeholder="Email..."
            >
        </li>

        <li>
            <div class="pw-search-btn">
                <div class="psb-col">
                    <button
                        type="submit"
                        name="search"
                        value="yes"
                        class="btn btn-small btn-primary pw-search-btn"
                    >
                        Search
                    </button>
                </div>

                <div class="psb-col">
                    <a
                        href="{{ url()->current() }}"
                        class="btn btn-small btn-default pw-search-btn"
                    >
                        Clear <span>Search</span>
                    </a>
                </div>
            </div>
        </li>
    </ul>
</form>
