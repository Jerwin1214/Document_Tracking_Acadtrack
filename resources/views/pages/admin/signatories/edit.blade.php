@extends('pages.admin.admin-content')

@section('content')

<div class="container mt-4">

    <h3 class="mb-4">Edit Signatory</h3>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <strong>Update Signatory Information</strong>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.signatories.update', $signatory->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col">
                        <label>First Name</label>
                        <input type="text" name="first_name"
                               value="{{ $signatory->first_name }}" class="form-control" required>
                    </div>

                    <div class="col-3">
                        <label>Middle Initial</label>
                        <input type="text" name="middle_initial"
                               value="{{ $signatory->middle_initial }}"
                               maxlength="2"
                               pattern="[A-Za-z]{1,2}"
                               title="Middle initial must be 1 or 2 letters only"
                               class="form-control">
                    </div>

                    <div class="col">
                        <label>Last Name</label>
                        <input type="text" name="last_name"
                               value="{{ $signatory->last_name }}" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label>Educational Attainment</label>
                        <input type="text" name="educational_attainment"
                               value="{{ $signatory->educational_attainment }}" class="form-control">
                    </div>

                    <div class="col">
                        <label>Position</label>
                        <input type="text" name="position"
                               value="{{ $signatory->position }}" class="form-control" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-success">Update</button>
                <a href="{{ route('admin.signatories.index') }}" class="btn btn-secondary">Back</a>

            </form>
        </div>

    </div>

</div>

@endsection
