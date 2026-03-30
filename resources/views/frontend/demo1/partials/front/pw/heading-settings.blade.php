<div class="position-relative">
    <a href="#" class="c-white-link f-12 f-semibold u-hover-opacity-70 d-block mb-2" data-target=".lang-setttings">SETTINGS</a>
    <div class="footer-submenu lang-setttings">
        <ul class="list-unstyled heading--settings-wrapper">
            <li class="border border-bottom-0 border-white position-relative">
                <select class="settings--select lang--settings">
                    @foreach(Config::get('conrad.languages') as $index => $language)
                    <option value="{{$language}}">{{$language}}</option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down position-absolute f-8 text-white"></i>
                <div id="google_translate_element" class="u-d-none"></div>
            </li>
            <li class="border border-white position-relative">
                <select class="settings--select currency-selector--footer">
                    <option value="">Currency</option>
                    @foreach(Config::get('conrad.currencies') as $index => $currency)
                        <option <?php if((request('currency') && request('currency') == $index) || (Session::has('exchange_rate') && Session::get('exchange_rate')['exchange_code'] == $index)){ echo 'selected' ; } ?> value="{{$index}}">{{$index}}</option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down position-absolute f-8 text-white"></i>
            </li>
        </ul>
    </div>
</div>
