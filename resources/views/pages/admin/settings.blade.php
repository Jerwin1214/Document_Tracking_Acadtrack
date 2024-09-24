@extends('pages.admin.admin-content')

@section('content')
<h2>Settings</h2>
<div class="shadow-lg p-3 mb-5 mt-3 bg-body-tertiary rounded">
    <form action="/admin/settings" method="post">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" value="{{auth()->user()->email}}" name="email" id="email" class="form-control" aria-describedby="emailHelp" required>
            <div id="emailHelp" class="form-text">Remember, once you change the email and submit the form, it will automatically log you out.</div>
            <x-form-error name="email" />
        </div>
        <div class="mb-3">
            <label for="old_password" class="form-label">Old Password</label>
            <input type="password" name="old_password" id="old_password" class="form-control" required>
            <x-form-error name="old_password" />
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
            <x-form-error name="password" />
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Re-enter the New Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
            <x-form-error name="password_confirmation" />
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="reset" class="btn btn-outline-secondary">Clear</button>
        </div>
    </form>
</div>
@endsection