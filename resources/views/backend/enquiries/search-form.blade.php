@php
    $search_url = http_build_query($request->all(), '', '&');
@endphp

<form
    action="{{ route('enquiries.index') }}"
    method="GET"
    class="search-form"
>

    <ul class="nav navbar-right panel_toolbox">
        <li class="top-button">
            <a
                href="{{ admin_url('enquiries/csv/download?' . $search_url) }}"
                class="btn btn-small btn-primary"
                target="_blank"
            >
                Download CSV
            </a>
        </li>
    </ul>

    <ul class="sf-field">

        {{-- Status filter (currently disabled in original code) --}}
        @if(0)
            <li class="medium">
                <select name="status" id="status" class="form-control select-pw">
                    @foreach(Config::get('data.enquiry_status') as $key => $label)
                        <option
                            value="{{ $key }}"
                            {{ ($request->input('status', 'active') == $key) ? 'selected' : '' }}
                        >
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </li>
        @endif

        <li class="large">
            <select
                name="category"
                id="category"
                class="form-control select-pw"
            >
                <option value="">Category</option>
                @foreach($categories as $key => $label)
                    <option
                        value="{{ $key }}"
                        {{ $request->input('category') == $key ? 'selected' : '' }}
                    >
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </li>

        <li>
            <select
                name="e_status"
                id="id-e_status"
                class="form-control select-pw"
            >
                @foreach(e_states('e_status') as $key => $label)
                    <option
                        value="{{ $key }}"
                        {{ request('e_status') == $key ? 'selected' : '' }}
                    >
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </li>

        <li class="small">
            <input
                type="text"
                name="date_from"
                value="{{ $request->input('date_from') }}"
                class="form-control datepicker"
                placeholder="Date From"
                autocomplete="off"
            >
        </li>

        <li class="small">
            <input
                type="text"
                name="date_to"
                value="{{ $request->input('date_to') }}"
                class="form-control datepicker"
                placeholder="Date To"
                autocomplete="off"
            >
        </li>

        <li class="medium">
            <input
                type="text"
                name="q"
                value="{{ $request->input('q') }}"
                class="form-control"
                placeholder="Keyword..."
            >
        </li>

        {{-- Optional filter (commented in original) --}}
        {{--
        <li class="medium">
            <select name="filter" id="filter" class="form-control select-pw">
                <option value="">Filter</option>
                <option value="normal" {{ $request->input('filter') == 'normal' ? 'selected' : '' }}>Normal</option>
                <option value="hot" {{ $request->input('filter') == 'hot' ? 'selected' : '' }}>Hot</option>
                <option value="scam" {{ $request->input('filter') == 'scam' ? 'selected' : '' }}>Scam</option>
            </select>
        </li>
        --}}

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
