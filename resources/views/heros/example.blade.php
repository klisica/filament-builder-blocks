@php
    $headingElement = @$has_main_heading ? 'h1' : 'h2';
@endphp

<section>
    <div @if (@$is_centered) style="text-align: center;" @endif>
        <{{ $headingElement }}>
            {{ $heading }}
        </{{ $headingElement }}>
    </div>
</section>
