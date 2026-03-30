@extends('backend.layouts.master')

@section('admin-content')

<form action="{{ route('postcategories.store') }}"
      method="POST">

    @csrf

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel pw">
                <div class="x_title">
                    <h2><br/></h2>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <p class="text-muted font-13 m-b-30"><br/></p>

                    <div class="x_panel pw-inner-tabs">
                        <div class="x_title">
                            <h2>Category</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li>
                                    <a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content pw-open">
                            <div class="xpw-fields">
                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="control-form">
                                            <label>
                                                Name: {!! required_label() !!}
                                            </label>
                                            <input type="text"
                                                   name="name"
                                                   id="id-first-name"
                                                   class="form-control"
                                                   placeholder="Please enter..."
                                                   value="{{ old('name') }}">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Sticky buttons --}}
    <div class="form-group sticky-buttons">
        <button type="submit"
                class="btn btn-large btn-primary"
                name="action">
            Save
        </button>

        <a href="{{ admin_url($folder) }}"
           class="btn btn-default btn-spacing">
            Cancel <span>and Return</span>
        </a>
    </div>

</form>

@endsection
