@props(['disabled' => false])

<input type="checkbox" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded border-white/10 bg-[#09120e] text-brand-600 shadow-inner focus:ring-brand-500/30 disabled:opacity-50 focus:ring-offset-0 focus:ring-2 transition-colors']) !!}>
