@extends('pages.admin.admin-content')

@section('content')

<div class="container mt-4">

    <h3 class="mb-4">Signatory Management</h3>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- ADD SIGNATORY FORM --}}
    <div class="card mb-4">
        <div class="card-header">
            <strong>Add New Signatory</strong>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.signatories.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col">
                        <label>First Name</label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>

                    <div class="col-3">
                        <label>Middle Initial</label>
                        <input type="text" name="middle_initial" class="form-control"
                               maxlength="2"
                               pattern="[A-Za-z]{1,2}"
                               title="Middle initial must be 1 or 2 letters only">
                    </div>

                    <div class="col">
                        <label>Last Name</label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label>Educational Attainment</label>
                        <input type="text" name="educational_attainment" class="form-control" placeholder="e.g., MAEd, PhD">
                    </div>

                    <div class="col">
                        <label>Position</label>
                        <input type="text" name="position" class="form-control" placeholder="Registrar, Principal, etc." required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Add Signatory</button>
            </form>
        </div>
    </div>


    {{-- SIGNATORY LIST TABLE --}}
    <div class="card">
        <div class="card-header">
            <strong>Existing Signatories</strong>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped m-0">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Educational Attainment</th>
                        <th>Position</th>
                        <th width="160">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($signatories as $sig)
                        <tr>
                            <td>
                                {{ $sig->first_name }}
                                {{ $sig->middle_initial ? $sig->middle_initial . '.' : '' }}
                                {{ $sig->last_name }}
                            </td>

                            <td>{{ $sig->educational_attainment }}</td>
                            <td>{{ $sig->position }}</td>

                            <td>
                                <a href="{{ route('admin.signatories.edit', $sig->id) }}"
                                   class="btn btn-warning btn-sm">Edit</a>

                                <form action="{{ route('admin.signatories.delete', $sig->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            onclick="return confirm('Delete this signatory?')"
                                            class="btn btn-danger btn-sm">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-3">No signatories added yet.</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

</div>

@endsection
