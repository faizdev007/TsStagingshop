<div class="mt-4 position-relative">
    <a href="#" class="c-white-link f-12 f-semibold u-hover-opacity-70" data-target=".lang-setttings">SETTINGS</a>
    <div class="footer-submenu lang-setttings">
        <ul>
            <li>
                <select class="settings--select lang--settings -border-bottom">
                    @foreach(Config::get('conrad.languages') as $index => $language)
                    <option value="{{$language}}">{{$language}}</option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down"></i>
                <div id="google_translate_element" class="u-d-none"></div>
            </li>
            <li>
                <select class="settings--select currency-selector--footer">
                    <option value="">Currency</option>
                    @foreach(Config::get('conrad.currencies') as $index => $currency)
                        <option <?php if((request('currency') && request('currency') == $index) || (Session::has('exchange_rate') && Session::get('exchange_rate')['exchange_code'] == $index)){ echo 'selected' ; } ?> value="{{$index}}">{{$index}}</option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down"></i>
            </li>
        </ul>
    </div>
</div>
