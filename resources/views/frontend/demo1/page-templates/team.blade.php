
@push('body_class')team-page @endpush

<section class="page-title c-bg-gray-8 -the-team lazyBg"
    @if(!empty($page->photo))
    style="background-image: url({{ blankImg() }})"
    {!!$page->BannerDataBG!!} @endif>
    <div class="container">
        <div class="-wrap">
            <div class="-inner-wrap">
                <h1 class="-page-title-heading f-four f-50 f-500 c-white text-center">
                    {!! $page->title !!}
					<span class="-border {{ !empty($page->header_title)?'border-remove-title':'' }}"></span>
				</h1>
                @if($page->header_title)
                    <h2 class="-page-subtitle-heading f-16 f-two f-500 c-white d-block text-center">{{ $page->header_title }}</h2>
                @endif
            </div>
        </div>
    </div>
</section>

<section class="page-content u-mt2 u-mb2  team-page-content">
    <div class="container">
        <div class="property-content-wrapper page-inner-container">
            <div class="team-list">
                        @if($team->count())
                @php $i=0; @endphp
                @foreach($team as $team)
                           @if($i != 5)
                          <div class="-body-box team-box u-pt2 u-pb2  @if($i == 4) last-team-row @endif">
                            <div class="row no-gutters justify-content">

                                <div class="col-12  col-sm-5 pw-aligner location-content">
                                    <div class="image-box">
                                         @if($team->team_member_photo)
                                            <img src="{{ storage_url($team->location_photo) }}" alt="{{ strip_tags($team->location_photo) }}">
                                        @else
                                            <img class="img-fluid" alt="{{ $team->team_member_name }}" src="{{ themeAsset('images/team/bridgwater-location.jpg') }}" />
                                        @endif

                                         <p class="f-regular text-center">{{$team->location}}</p>
                                    </div>
                                </div>
                                  <div class="col-12 col-sm-7 team-content">
                                  @endif

                                    <div class="row no-gutters">
                                       <div class="col-md-5 col-div team-member-image">
                                         @if($team->team_member_photo)
                                            <img src="{{ storage_url($team->team_member_photo) }}" alt="{{ strip_tags($team->team_member_name) }}">
                                        @else
                                            <img class="img-fluid" alt="{{ $team->team_member_name }}" src="{{ themeAsset('images/placeholder/large.png') }}" />
                                        @endif
                                       </div>
                                       <div class="col-md-7 col-div">
                                    <div class="team-content-text text-center">
                                         @if($i < 2)
                                        <div class="managed-by">MANAGED BY</div>
                                         @endif
                                       <div class="border-bottom border-top team-name">
                                       <h4 class="f-regular"> {{ $team->team_member_name }}</h4>
                                       <span class="f-regular"> {!! $team->team_member_role !!}</span>
                                   </div>
                                       <ul class="social-icon">
                                        @if(!empty($team->team_member_phone))
                                         <li> <a href="tel:{{$team->team_member_phone}}" target="_blank"><i class="fas fa-phone-alt"></i></a></li>
                                        @endif
                                        @if(!empty($team->team_member_email))
                                         <li> <a href="tel:{{$team->team_member_email}}" target="_blank"><i class="fas fa-envelope"></i></a></li>
                                        @endif
                                    </ul>
                                    </div>
                                </div>
                                     </div>

                              @if($i != 4)
                              </div>
                            </div>
                        </div>
                        @endif
            @php $i++; @endphp
                @endforeach
            @else
                <p>No team members at this time.</p>
            @endif

</div>
        </div>
    </div>
</section>

<section class="content-right-text-section">
    <div class="container-fluid">
        <div class="-wrap">
            <div class="row no-gutters">
                <div class="col-md-5">
                    <div class="-image">
                        <img class="b-lazy" src="{{ blankImg() }}" data-src="{{ $aboutpage->DisplayPhotoHome }}" alt="{{ $aboutpage->title }}">
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="-content pw-aligner">
                        <div class="-c-wrap">
                            <h2 class="-title f-40 f-400 f-two">{{ $aboutpage->title }}</h2>
                            <div class="-c-body f-16">
                                {!! $aboutpage->content !!}
                            </div>
                            <div class="-c-cta">
                                <a href="{{lang_url('the-altman-real-estate-group')}}" class="cta -secondary -wider-3">FIND OUT MORE</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@include('frontend.demo1.forms.generic-bottom')
