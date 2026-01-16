{{--
    Componente de imagen optimizada para PageSpeed/GTmetrix
    
    Uso:
    <x-optimized-image 
        src="/img/ejemplo.jpg" 
        alt="Descripción de la imagen"
        width="800"
        height="600"
        :lazy="true"
        :priority="false"
        class="rounded-lg"
    />
    
    Props:
    - src: URL de la imagen (requerido)
    - alt: Texto alternativo (requerido para accesibilidad)
    - width: Ancho de la imagen (recomendado para evitar CLS)
    - height: Alto de la imagen (recomendado para evitar CLS)
    - lazy: true para lazy loading (por defecto true)
    - priority: true para imágenes above-the-fold (desactiva lazy y añade fetchpriority="high")
    - class: Clases CSS adicionales
    - srcset: Set de fuentes para responsive images
    - sizes: Tamaños para responsive images
--}}

@props([
    'src',
    'alt' => '',
    'width' => null,
    'height' => null,
    'lazy' => true,
    'priority' => false,
    'class' => '',
    'srcset' => null,
    'sizes' => null,
])

@php
    // Si es priority, no usar lazy loading
    $loading = $priority ? 'eager' : ($lazy ? 'lazy' : 'eager');
    $fetchpriority = $priority ? 'high' : 'auto';
    $decoding = $priority ? 'sync' : 'async';
    
    // Determinar si la imagen puede tener versión WebP
    $extension = pathinfo($src, PATHINFO_EXTENSION);
    $canHaveWebp = in_array(strtolower($extension), ['jpg', 'jpeg', 'png']);
    $webpSrc = $canHaveWebp ? preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $src) : null;
    
    // Construir atributos de dimensiones
    $dimensionAttrs = [];
    if ($width) $dimensionAttrs['width'] = $width;
    if ($height) $dimensionAttrs['height'] = $height;
@endphp

@if($canHaveWebp && $webpSrc)
{{-- Usar picture element para soporte de WebP con fallback --}}
<picture>
    <source 
        type="image/webp" 
        srcset="{{ $webpSrc }}"
        @if($sizes) sizes="{{ $sizes }}" @endif
    />
    <img 
        src="{{ $src }}"
        alt="{{ $alt }}"
        loading="{{ $loading }}"
        decoding="{{ $decoding }}"
        fetchpriority="{{ $fetchpriority }}"
        @if($srcset) srcset="{{ $srcset }}" @endif
        @if($sizes) sizes="{{ $sizes }}" @endif
        @foreach($dimensionAttrs as $attr => $value) {{ $attr }}="{{ $value }}" @endforeach
        {{ $attributes->merge(['class' => $class]) }}
    />
</picture>
@else
{{-- Imagen simple para SVG, GIF, WebP nativos, etc. --}}
<img 
    src="{{ $src }}"
    alt="{{ $alt }}"
    loading="{{ $loading }}"
    decoding="{{ $decoding }}"
    fetchpriority="{{ $fetchpriority }}"
    @if($srcset) srcset="{{ $srcset }}" @endif
    @if($sizes) sizes="{{ $sizes }}" @endif
    @foreach($dimensionAttrs as $attr => $value) {{ $attr }}="{{ $value }}" @endforeach
    {{ $attributes->merge(['class' => $class]) }}
/>
@endif
