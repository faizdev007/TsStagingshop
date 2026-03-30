@if(settings('translations'))
    <?php $currentLocale = App::getLocale(); ?>
    <div class="dropdown translate__dropdown d-block d-md-inline-block d-xl-inline-block">
        @if($translate_settings->language_default != $currentLocale)
            <a class="dropdown-toggle chosen__language" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="{{asset('assets/images/flags/'.$currentLocale.'.png')}}" alt="{{ $currentLocale }}" />
            </a>
        @else
            <a class="dropdown-toggle chosen__language" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="{{asset('assets/images/flags/'.$translate_settings->language_default.'.png')}}" alt="{{ $translate_settings->language_default }}" />
            </a>
        @endif
        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a rel="alternate" hreflang="{{ $translate_settings->language_default }}" class="dropdown-item language__select" href="{{ LaravelLocalization::getNonLocalizedURL() }}">
                <img src="{{asset('assets/images/flags/'.$translate_settings->language_default.'.png')}}" alt="{{ $translate_settings->language_default }}" />
            </a>
            @foreach($translate_settings->languages_array as $language)
                <a rel="alternate" hreflang="{{ $language }}" class="dropdown-item language__select" href="{{ LaravelLocalization::getLocalizedURL($language, null, [], true) }}">
                    <img src="{{asset('assets/images/flags/'.$language.'.png')}}" alt="{{ $language }}" />
                </a>
            @endforeach
        </div>
    </div>
@endif
