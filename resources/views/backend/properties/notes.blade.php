@extends('backend.properties.template')

@section('property-content')

<form
    action="{{ route('properties.agentnote.update', $property->id) }}"
    method="POST"
>
    @csrf
    @method('PUT')

    <div class="x_panel pw-inner-tabs">
        <div class="x_title">
            <h2>Notes</h2>
            <div class="clearfix"></div>
        </div>

        <div class="x_content pw-open">
            <div class="xpw-fields">
                <div class="row">
                    <div class="col-md-12">
                        <textarea
                            name="agent_notes"
                            id="id-agent_notes"
                            class="mceEditor description"
                            placeholder="Please enter..."
                            maxlength="60000"
                        >{{ old('agent_notes', $property->agent_notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group sticky-buttons">
        <button
            type="submit"
            class="btn btn-large btn-primary"
            name="action"
            value="update_property"
        >
            Save
        </button>

        @include('backend.properties.sticky-buttons')
    </div>
</form>

@endsection
