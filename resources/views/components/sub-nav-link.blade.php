@props(['href' => '#'])

<a {{ $attributes->merge(['href' => $href, 'class' => 'nav-link']) }}>
    {{ $slot }}
</a>
