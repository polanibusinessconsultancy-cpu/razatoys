@props([
    'imagePath' => null,
    'alt' => 'Course Image',
    'class' => 'img-fluid',
    'lazy' => true,
    'decoding' => 'async'
])

@php
    // Extract base filename from image path
    // e.g., "course-images/course-123456-abc-large.webp" -> "course-123456-abc"
    if ($imagePath) {
        $basename = basename($imagePath);
        $basename = preg_replace('/-(?:small|medium|large|small_png|large_png)\.(?:webp|png)$/', '', $basename);
        $directory = dirname($imagePath);
    } else {
        $basename = null;
        $directory = null;
    }
@endphp

@if ($imagePath && $basename && $directory)
    <picture>
        <!-- WebP with responsive sizes for modern browsers -->
        <source
            srcset="
                {{ asset('storage/' . $directory . '/' . $basename . '-small.webp') }} 200w,
                {{ asset('storage/' . $directory . '/' . $basename . '-medium.webp') }} 400w,
                {{ asset('storage/' . $directory . '/' . $basename . '-large.webp') }} 600w
            "
            sizes="(max-width: 600px) 100vw, (max-width: 1024px) 50vw, 33vw"
            type="image/webp"
        >

        <!-- PNG fallback for older browsers -->
        <source
            srcset="{{ asset('storage/' . $directory . '/' . $basename . '-large_png.png') }}"
            type="image/png"
        >

        <!-- Fallback img tag -->
        <img
            src="{{ asset('storage/' . $directory . '/' . $basename . '-large.webp') }}"
            alt="{{ $alt }}"
            class="{{ $class }}"
            {{ $lazy ? 'loading="lazy"' : '' }}
            decoding="{{ $decoding }}"
            width="600"
            height="450"
        >
    </picture>
@else
    <!-- Placeholder for missing images -->
    <img
        src="https://via.placeholder.com/600x450?text=No+Image"
        alt="{{ $alt }}"
        class="{{ $class }}"
        width="600"
        height="450"
    >
@endif
