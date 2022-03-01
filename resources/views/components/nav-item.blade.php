@props([
    'href' => '#',
    'icon' => 'fas fa-hashtag',
    'text' => '',
])

<li class="nav-item">
    <a href="{{ $href }}" class="nav-link">
        <i class="{{ $icon }}"></i>
        <p class="text ml-2">{{ $text }}</p>
    </a>
</li>