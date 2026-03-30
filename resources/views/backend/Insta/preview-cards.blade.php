@extends('backend.layouts.master')



@section('admin-content')

<div class="container-fluid">

    <div class="row">

        <div class="col-12">

            <h1>Insta Cards Preview - {{ $property->ref }}</h1>

        </div><img 

    </div>



    <div class="row">

        @foreach($instaCards as $card)

        <div class="col-md-4 mb-4">

            <div class="card">

                <img src="{{ $card['image'] }}" class="card-img-top" alt="Insta Card">

                <div class="card-body">

                    <h5 class="card-title">{{ $property->name }}</h5>

                    <p class="card-text">

                        Location: {{ $property->location }}<br>

                        Beds: {{ $property->beds }} | Baths: {{ $property->baths }}<br>

                        Price: {{ $property->price }}

                    </p>

                </div>

            </div>

        </div>

        @endforeach

    </div>

</div>

@endsection