@extends('frontend.demo1.layouts.frontend')
@push('body_class')property-alerts @endpush
@section('main_content')
    @include('frontend.demo1.account.parts.banners')
    <section class="u-pt0 u-pb2">
        <div class="container">
            @if (session('message_success'))
                <div class="row">
                    <div class="col">
                        <div class="alert alert-success">
                            {{ session('message_success') }}
                        </div>
                    </div>
                </div>
            @endif
            @include('frontend.demo1.account.nav.nav')
            <div class="row u-mt1">
                <div class="col">
                    @if($messages->count() > 0 )
                        <div class="c-bg-dark-gray u-p2 u-mb2">
                            <div class="text-center">
                                <span class="c-white f-24 f-bold u-block u-mb1">Send a reply to {{ settings('site_name') }}</span>
                            </div>
                            <form method="post" action="{{ url('account/messages') }}" data-toggle="validator">
                                @csrf
                                <div class="row u-mt1">
                                    <div class="col-sm-12 col-md-1">
                                        <div class="message__user">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-11">
                                        <div class="form-group">
                                            <textarea name="message_content" class="form__input -messaging -no-border u-fullwidth u-rounded-0 u-pt05 u-no-resize" placeholder="Reply to the agents message...." required></textarea>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row u-mt05">
                                    <div class="col">
                                        <div class="u-pull-right">
                                            <button type="submit" class="button -primary -square f-bold -large u-pr2 u-pl2 u-block-mobile text-uppercase">Send Reply</button>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="to_id" value="{{ $last_id }}">
                            </form>
                        </div>
                        @foreach($messages as $message)
                            <div class="c-bg-gray u-mobile-center u-p2 u-mb2">
                                <div class="row">
                                    <div class="col-sm-12 col-md-1">
                                        @if($message->from_id == Auth::user()->id)
                                            <div class="message__user">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @else
                                            <div class="message__user -company">

                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-sm-12 col-md-11">
                                        <div class="u-pl2 u-pr2">
                                            <strong class="u-block u-mb0 @if($message->from_id != Auth::user()->id) c-secondary @endif">
                                                @if($message->sender)
                                                    {{ $message->sender->name }}
                                                @endif
                                                @if($message->from_id != Auth::user()->id) - Agent @endif
                                            </strong>
                                            <em class="f-13 c-dark-gray">{{ $message->friendly_date }} at {{ $message->friendly_time }}</em>
                                            <div class="u-mt1">
                                                {!! nl2br($message->message_content) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="c-bg-dark-gray u-p2 u-mb2">
                            <div class="text-center">
                                <span class="c-white f-24 f-bold u-block u-mb1">Contact an agent today</span>
                            </div>
                            <form method="post" action="{{ url('account/messages') }}" data-toggle="validator">
                                @csrf
                                <div class="row u-mt1">
                                    <div class="col-sm-12 col-md-1">
                                        <div class="message__user">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-11">
                                        <div class="form-group">
                                            <textarea name="message_content" class="form__input -messaging -no-border u-fullwidth u-rounded-0 u-pt05 u-no-resize" placeholder="Start a conversation with one of our agents...." required></textarea>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row u-mt05">
                                    <div class="col">
                                        <div class="u-pull-right">
                                            <button type="submit" class="button -primary f-bold -large u-pr2 u-pl2 u-block-mobile text-uppercase">Send Message</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

@endsection
