@props(['disabled' => false])

<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'block w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100 shadow-inner backdrop-blur-sm placeholder:text-slate-500 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/30 disabled:cursor-not-allowed disabled:opacity-50 transition-colors']) !!}>{{ $slot }}</textarea>
