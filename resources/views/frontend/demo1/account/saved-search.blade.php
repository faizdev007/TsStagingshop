@extends('frontend.demo1.layouts.frontend')
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
                @if($searches->count() > 0 )
                    <div class="col">
                        <div class="info-box c-bg-gray">
                            <table class="table searches-table">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Location</th>
                                        <th class="mobile-table">Info</th>
                                        <th class="desktop" width="200">Property Type</th>
                                        <th class="desktop">Min Price</th>
                                        <th class="desktop">Max Price</th>
                                        <th class="desktop">Beds</th>
                                        <th class="desktop">Baths</th>
                                        <th class="desktop">&nbsp;</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($searches as $search)
                                        <?php $search_data = json_decode($search->saved_search_criteria); ?>
                                        <tr id="save-search-{{ $search->saved_search_id }}">
                                            <td class="align-middle">{{ ucfirst($search_data->for) }}</td>
                                            <td class="align-middle">
                                                @if(!empty($search_data->in))
                                                    {{ ucfirst($search_data->in) }}
                                                @else
                                                    Not Set
                                                @endif
                                            </td>
                                            <td class="align-middle desktop f-14">
                                                @if(!empty($search_data->property_type))
                                                    {{ arrayToSentence($search_data->property_type, 'property type') }}
                                                @else
                                                    Not Set
                                                @endif
                                            </td>
                                            <td class="align-middle desktop">
                                                @if(!empty($search_data->min_price))
                                                    {{ settings('currency_symbol') }}
                                                    {{ thousandscurrencyformat( $search_data->min_price ) }}
                                                @else
                                                    Not Set
                                                @endif
                                            </td>
                                            <td class="align-middle desktop">
                                                @if(!empty($search_data->max_price))
                                                    @if($search_data->max_price == '200000000')
                                                        {!! settings('currency_symbol') !!} 5,000,000+
                                                    @else
                                                        {{ settings('currency_symbol') }}
                                                        {{ thousandscurrencyformat($search_data->max_price) }}
                                                    @endif

                                                @else
                                                    Not Set
                                                @endif
                                            </td>
                                            <td class="align-middle desktop">
                                                @if($search_data->beds)
                                                    {{ ucfirst($search_data->beds) }}
                                                @else
                                                    Not Set
                                                @endif
                                            </td>
                                            <td class="align-middle desktop">
                                                @if($search_data->baths)
                                                    {{ ucfirst($search_data->baths) }}
                                                @else
                                                    Not Set
                                                @endif
                                            </td>
                                            <td class="align-middle text-right desktop">
                                                @if($search->is_converted == 'n')
                                                    <a class="button -primary -normal f-13 f-bold u-inline-block u-mr05" href="{{ url('account/alert-conversion/'.$search->saved_search_id) }}">Convert to Property Alert</a>
                                                @endif
                                                <a href="{{ url($search->saved_search_url) }}" class="u-mr05">
                                                    <span class="c-primary f-18"><i class="fas fa-search"></i></span>
                                                </a>
                                                <a class="modal-toggle" href="#"
                                                data-item-id="{{ $search->saved_search_id }}"
                                                data-toggle="modal"
                                                data-modal-type="delete"
                                                data-modal-title="Delete Saved Search"
                                                data-modal-size="small"
                                                data-item="save-search"
                                                data-delete-type="save-search"
                                                data-target="#global-modal">
                                                <span class="c-primary f-18"><i class="far fa-trash-alt"></i></span>
                                                </a>
                                            </td>
                                            <td class="mobile">
                                                @php
                                                    $tooltip = '';
                                                    $tooltip .= '<div class="tooltip_templates u-text-left u-p1">';
                                                        $tooltip .='<span id="tooltip_content-'.$search->saved_search_id.'">';

                                                            $search_dataype = !empty($search_data->property_type)?arrayToSentence($search_data->property_type):'Not Set';

                                                            $search_dataBeds = 'Not Set';
                                                            if($search_data->beds){
                                                                $search_dataBeds = $search_data->beds;
                                                            }
                                                            $tooltip .='<strong>Property Type:</strong>'.$search_dataype.'<br/>';

                                                            if(!empty($search_data->price_from)){
                                                                $tooltip .='<strong>Min Price:</strong>'.$search_data->price_from.'<br/>';
                                                            }

                                                            if(!empty($search_data->price_to)){
                                                                $tooltip .='<strong>Max Price:</strong>'.$search_data->price_to.'<br/>';
                                                            }

                                                            $tooltip .='<strong>Beds:</strong>'.$search_dataBeds.'<br/>';

                                                            if($search_data->baths){
                                                                $tooltip .='<strong>Baths:</strong>'.$search_data->baths.'<br/>';
                                                            }

                                                        $tooltip .= '</span>';
                                                    $tooltip .= '</div>';
                                                @endphp
                                                <i
                                                data-toggle="tooltip"
                                                data-html="true"
                                                data-placement="top"
                                                title="{{$tooltip}}"
                                                class="fas fa-info-circle"></i>

                                            </td>
                                        </tr>
                                        <tr id="save-search-mobile-{{ $search->saved_search_id }}" class="mobile-table-row">
                                            <td colspan="6">
                                                @if($search->is_converted == 'n')
                                                    <a class="button -primary -normal f-13 f-bold -square" href="{{ url('account/alert-conversion/'.$search->saved_search_id) }}">Make Property Alert</a>
                                                @endif
                                                <a href="{{ url($search->saved_search_url) }}" class="button -primary -normal f-13 text-uppercase f-bold">
                                                    <span class="c-white f-13"><i class="fas fa-search"></i></span>
                                                </a>
                                                <a class="button -primary -normal f-13 text-uppercase f-bold modal-toggle" href="#"
                                                   data-item-id="{{ $search->saved_search_id }}"
                                                   data-toggle="modal"
                                                   data-modal-type="delete"
                                                   data-modal-title="Delete Saved Search"
                                                   data-modal-size="small"
                                                   data-item="save-search"
                                                   data-delete-type="save-search"
                                                   data-target="#global-modal">
                                                    <span class="c-white f-13"><i class="far fa-trash-alt"></i></span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                <div class="col">
                    <p>You currently have no searches saved. <a class="c-primary f-bold" href="{{url('property-for-sale')}}">Start your property search now!</a></p>
                </div>
                @endif
            </div>
        </div>
    </section>
@endsection
