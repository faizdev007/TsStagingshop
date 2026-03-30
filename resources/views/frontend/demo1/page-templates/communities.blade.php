@push('body_class')-full-banner @endpush

<section class="page-title c-bg-gray-8 -communities lazyBg"
    @if(!empty($page->photo))
    style="background-image: url({{ blankImg() }})"
    {!!$page->BannerDataBG!!} @endif>
    <div class="container">
        <div class="-wrap">
            <div class="-inner-wrap">
                <h1 class="-page-title-heading f-two f-60 f-500 c-white text-center">
                    {!! $page->title !!}
					<span class="-border {{ !empty($page->header_title)?'border-remove-title':'' }}"></span>
				</h1>
                @if($page->header_title)
                    <h2 class="-page-subtitle-heading f-26 f-four f-500 c-white d-block text-center">{{ $page->header_title }}</h2>
                @endif
            </div>
        </div>
    </div>
</section>

@if(count($items))
<section class="page-content u-mt405 u-mt-md-2 u-mt-xs-1 u-mb4">
    <div class="container">
        <div class="communuties-box-1">
            <div class="row">
                @foreach($items as $item)
                <div class="col-md-3 col-sm-6 col-6">
                    <div class="communities-grid">
                        <div class="-image-box">
                            <img src="{{ $item->PhotoDisplay }}" alt="{{$item->name}}">
                        </div>
                        <div class="-label">
                            <div class="-hb-title f-two text-center">{{$item->name}}</div>
                        </div>
                        <div class="-hover-box">
                            <div class="-hb-wrap">
                                <div class="-hb-title f-two text-center">{{$item->name}}</div>
                                <div class="-hb-cta text-center">
                                    <a href="{{lang_url($item->url)}}">VIEW DETAILS</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</section>
@endif

@include('frontend.demo1.forms.generic-bottom')
