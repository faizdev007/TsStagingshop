@extends('backend.layouts.master')



@section('content')

<div class="container-fluid">

    <div class="row">

        <div class="col-12">

            <div class="card">

                <div class="card-body">

                    <h4 class="card-title mb-4">Insta Cards</h4>



                    <table class="table table-bordered">

                        <thead>

                            <tr>

                                <th>Ref</th>

                                <th>Name</th>

                                <th>Price</th>

                                <th>Action</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($properties as $property)

                            <tr>

                                <td>{{ $property->ref }}</td>

                                <td>{{ $property->name }}</td>

                                <td>{{ $property->price }}</td>

                                <td>

                                    <a href="{{ url('admin/insta-cards/generate/'.$property->ref) }}" 

                                       class="btn btn-primary btn-sm">

                                        Generate Cards

                                    </a>

                                </td>

                            </tr>

                            @endforeach

                        </tbody>

                    </table>



                    {{ $properties->links('pagination::bootstrap-4') }}

                </div>

            </div>

        </div>

    </div>

</div>

@endsection