@props(['icon_class', 'idNumber' => 1, 'link_name'])

<a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts{{$idNumber}}"
    aria-expanded="false" aria-controls="collapseLayouts{{$idNumber}}">
    <div class="sb-nav-link-icon"><i class="{{ $icon_class }}"></i></div>
    {{ $link_name }}
    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
</a>
<div class="collapse" id="collapseLayouts{{$idNumber}}" aria-labelledby="heading{{$idNumber}}"
    data-bs-parent="#sidenavAccordion">
    {{ $slot }}
</div>