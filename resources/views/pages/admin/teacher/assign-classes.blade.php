@extends('pages.admin.admin-content')

@section('content')
    <h2>Assign Classes</h2>

    <form action="/admin/teachers/assign-class" method="post" class="shadow-lg p-3 mb-5 mt-3 bg-body-tertiary rounded">
    @csrf
    </form>

@endsection
