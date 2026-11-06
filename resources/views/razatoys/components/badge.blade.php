@props([
  'type' => 'yellow', // yellow, pink, blue, green
  'text' => '',
])

<span {{ $attributes->merge(['class' => 'raza-badge raza-badge--' . $type]) }}>
  {{ $slot->isEmpty() ? $text : $slot }}
</span>
