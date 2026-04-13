@php
    $accentMap = [
        'brand' => 'bg-brand-600',
        'emerald' => 'bg-emerald-600',
        'teal' => 'bg-teal-700',
        'lime' => 'bg-lime-600',
        'sky' => 'bg-sky-600',
        'slate' => 'bg-slate-700',
        'amber' => 'bg-amber-500',
        'rose' => 'bg-rose-600',
        'orange' => 'bg-orange-500',
        'violet' => 'bg-violet-600',
    ];

    $accentClass = $accentMap[$application->accent_color] ?? 'bg-brand-600';
@endphp

<a href="{{ route('portal.launch', $application) }}" target="{{ $application->open_in_new_tab ? '_blank' : '_self' }}" class="app-tile group relative overflow-hidden flex flex-col justify-between h-full rounded-[24px] p-6">
    <div class="bracketPulse absolute inset-0 z-0"></div>
    <div class="relative z-10">
        <div class="flex items-start justify-between mb-4">
            <div class="{{ $accentClass }} inline-flex h-12 w-12 items-center justify-center rounded-xl border border-white/10 shadow-lg text-white group-hover:shadow-[0_0_15px_rgba(34,211,238,0.4)] transition-all duration-300">
                @include('portal.partials.app-icon', ['icon' => $application->icon])
            </div>
            @if ($application->badge)
                <span class="rounded-full border border-brand-400/30 bg-brand-400/10 px-2.5 py-1 text-[10px] font-bold text-brand-300 uppercase tracking-widest shadow-[0_0_10px_rgba(40,147,83,0.1)]">{{ $application->badge }}</span>
            @endif
        </div>
        <h4 class="text-xl font-bold tracking-tight text-slate-100 group-hover:text-white transition-colors">{{ $application->name }}</h4>
        <p class="mt-2 text-sm text-slate-400 leading-relaxed font-medium">{{ $application->description }}</p>
    </div>
    <div class="relative z-10 mt-6 pt-4 border-t border-white/10 flex items-center justify-between">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-slate-600 group-hover:bg-cyan-400 transition-colors"></span> 
            {{ $application->usesSso() ? 'Mode: SSO' : 'Mode: Login' }}
        </p>
        <span class="text-sm font-bold text-cyan-500 group-hover:text-cyan-400 flex items-center gap-1 transition-colors">
            Buka aplikasi
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </span>
    </div>
</a>
