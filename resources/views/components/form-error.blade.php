@props(['name'])

@error($name)
<p class="text-danger fw-bold fst-italic">{{$message}}</p>
@enderror