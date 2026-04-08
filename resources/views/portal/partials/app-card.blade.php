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

<a href="{{ route('portal.launch', $application) }}" target="{{ $application->open_in_new_tab ? '_blank' : '_self' }}" class="app-card glass-panel group rounded-[28px] border border-white/8 p-5 shadow-card">
    <div class="flex items-start justify-between gap-3">
        <div class="flex items-center gap-4">
            <div class="{{ $accentClass }} flex h-12 w-12 items-center justify-center rounded-2xl text-white shadow-sm">
                @include('portal.partials.app-icon', ['icon' => $application->icon])
            </div>
            <div>
                <h4 class="text-lg font-bold tracking-tight text-white">{{ $application->name }}</h4>
                <p class="mt-1 text-sm text-slate-400">{{ $application->description }}</p>
            </div>
        </div>
        @if ($application->badge)
            <span class="rounded-full border border-brand-400/20 bg-brand-400/10 px-3 py-1 text-[11px] font-semibold text-brand-200">{{ $application->badge }}</span>
        @endif
    </div>
    <div class="muted-line mt-5 flex items-center justify-between border-t pt-4">
        <p class="text-xs text-slate-500">
            {{ $application->usesSso() ? 'Mode: SSO' : 'Mode: Login Required' }}
        </p>
        <span class="text-sm font-semibold text-brand-300 group-hover:text-brand-200">Buka aplikasi</span>
    </div>
</a>
