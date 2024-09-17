<x-private-layout>
    <h1>This is {{ auth()->user()->name }}</h1>
    <a href="/logout" class="btn btn-outline-danger">Logout</a>
</x-private-layout>