@extends('pages.admin.admin-content')

@section('content')

<h2>Archived Teachers</h2>

@if (session('success'))
<x-popup-message type="success" :message="session('success')" />
@endif

<table class="table table-responsive">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Subjects</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @php
        $i = ($archivedTeachers->currentpage() - 1) * $archivedTeachers->perpage() + 1;
        @endphp

        @foreach ($archivedTeachers as $teacher)
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $teacher->salutation }} {{ $teacher->initials }} {{ $teacher->first_name }} {{ $teacher->last_name }}</td>
            <td>{{ $teacher->user->email }}</td>
            <td>
                <ul>
                    @foreach ($teacher->subjects as $subject)
                    <li title="{{$subject->name}}">{{$subject->code}}</li>
                    @endforeach
                </ul>
            </td>
            <td>
                <form action="{{ route('admin.teachers.unarchive', $teacher->id) }}" method="POST" onsubmit="return confirm('Reactivate this teacher?');">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success btn-sm">Unarchive</button>
                </form>
            </td>
        </tr>
        @endforeach

    </tbody>
</table>

<div class="container">
    {{ $archivedTeachers->links() }}
</div>

@endsection
