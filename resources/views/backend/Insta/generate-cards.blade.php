@extends('backend.layouts.app')



@section('title') Generate Insta Cards @endsection



@section('content')

<div class="container-fluid">

    <div class="row">

        <div class="col-12">

            <div class="page-title-box">

                <h4 class="page-title">Generate Insta Cards - {{ $property->ref }}</h4>

            </div>

        </div>

    </div>



    <div class="row">

        <div class="col-12">

            <div class="card">

                <div class="card-body">

                    @if(empty($instaCards))

                        <div class="alert alert-warning">No images found for this property.</div>

                    @else

                        <div class="row">

                            @foreach($instaCards as $index => $card)

                            <div class="col-md-6 mb-4">

                                <div class="card">

                                    <img src="{{ $card['image'] }}" class="card-img-top" alt="Property Image">

                                    <div class="card-body">

                                        <h5 class="card-title">{{ $property->name }}</h5>

                                        <p class="card-text">

                                            Ref: {{ $property->ref }}<br>

                                            Price: {{ $property->price }}

                                        </p>

                                    </div>

                                </div>

                            </div>

                            @endforeach

                        </div>



                        <div class="text-center mt-3">

                            <button id="downloadAll" class="btn btn-success">

                                Download All Cards

                            </button>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection



@push('scripts')

<script>

$(document).ready(function() {

    $('#downloadAll').on('click', function() {

        window.location.href = `/admin/insta-cards/bulk?refs[]={{ $property->ref }}`;

    });

});

</script>

@endpush