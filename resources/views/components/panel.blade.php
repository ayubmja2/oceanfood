@php
    $classes = 'p-6 bg-white/20 rounded-xl border border-transparent hover:border-gamboge group transition-colors duration-300';
@endphp
<div {{$attributes(['class' => $classes])}}>
    {{$slot}}
</div>
