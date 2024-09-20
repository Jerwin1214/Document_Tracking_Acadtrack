@props(['name'])

@error($name)
<p class="text-danger fst-italic">{{$message}}</p>
@enderror