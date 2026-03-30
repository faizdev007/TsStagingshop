@extends('backend.layouts.app')

@section('content')
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>{{ $pageTitle }}</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Property Update History</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="table-responsive">
                            <table class="table table-striped jambo_table">
                                <thead>
                                    <tr class="headings">
                                        <th>Timestamp</th>
                                        <th>Property Ref</th>
                                        <th>Updated By</th>
                                        <th>Changes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($logs as $log)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($log['datetime'])->format('Y-m-d H:i:s') }}</td>
                                            <td>{{ $log['property_ref'] }}</td>
                                            <td>{{ $log['user'] }}</td>
                                            <td>
                                                @if(is_array($log['changes']))
                                                    @foreach($log['changes'] as $field => $change)
                                                        <div class="change-item">
                                                            <strong>{{ ucfirst($field) }}:</strong><br>
                                                            Old: {{ is_array($change['old']) ? json_encode($change['old']) : $change['old'] }}<br>
                                                            New: {{ is_array($change['new']) ? json_encode($change['new']) : $change['new'] }}
                                                        </div>
                                                        @if(!$loop->last)<hr>@endif
                                                    @endforeach
                                                @else
                                                    {{ $log['changes'] }}
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No property update logs found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .change-item {
        margin-bottom: 5px;
    }
    .change-item hr {
        margin: 10px 0;
    }
</style>
@endpush
@endsection
