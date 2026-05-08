@props(['variant' => 'full'])

@if($variant === 'mark')
    <img src="{{ asset('images/logo-comunitec.svg') }}" alt="COMUN&ITEC" {{ $attributes->merge(['class' => 'h-10 w-auto']) }}>
@else
    <img src="{{ asset('images/logo-comunitec.svg') }}" alt="COMUN&ITEC" {{ $attributes->merge(['class' => 'h-20 w-auto']) }}>
@endif
