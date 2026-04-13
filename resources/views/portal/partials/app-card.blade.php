@php
    $accentMap = [
        'brand' => 'from-brand-500 to-brand-600',
        'emerald' => 'from-emerald-400 to-emerald-600',
        'teal' => 'from-teal-400 to-teal-600',
        'lime' => 'from-lime-400 to-lime-600',
        'sky' => 'from-sky-400 to-sky-600',
        'slate' => 'from-slate-400 to-slate-600',
        'amber' => 'from-amber-400 to-amber-600',
        'rose' => 'from-rose-400 to-rose-600',
        'orange' => 'from-orange-400 to-orange-600',
        'violet' => 'from-violet-400 to-violet-600',
    ];

    $accentClass = $accentMap[$application->accent_color] ?? 'from-brand-500 to-brand-600';
@endphp

<a href="{{ route('portal.launch', $application) }}" target="{{ $application->open_in_new_tab ? '_blank' : '_self' }}" class="app-tile group relative overflow-hidden flex flex-col items-center justify-center h-full rounded-[16px] p-6 lg:p-8 min-h-[200px] lg:min-h-[240px]">
    <!-- Cyberpunk Brackets -->
    <div class="hologram-bracket tl"></div>
    <div class="hologram-bracket tr"></div>
    <div class="hologram-bracket bl"></div>
    <div class="hologram-bracket br"></div>

    <div class="relative z-10 flex flex-col items-center justify-center w-full mt-2">
        <!-- Icon Container -->
        <div class="bg-gradient-to-b {{ $accentClass }} inline-flex h-[64px] w-[64px] lg:h-[72px] lg:w-[72px] items-center justify-center rounded-[16px] shadow-lg text-white mb-6 transition-all duration-300 group-hover:scale-110 group-hover:shadow-[0_0_30px_rgba(0,255,255,0.6)]">
            @include('portal.partials.app-icon', ['icon' => $application->icon])
        </div>
        
        <!-- App Name -->
        <h4 class="text-[15px] lg:text-[17px] font-bold tracking-wide text-white uppercase group-hover:text-cyan-400 group-hover:text-glow-cyan transition-colors text-center w-full whitespace-nowrap overflow-hidden text-ellipsis">
            {{ $application->name }}
        </h4>
        
        <!-- Description / Subtitle -->
        <p class="mt-2 text-[10px] text-slate-400 tracking-widest uppercase font-medium text-center w-full px-1 line-clamp-1">
            {{ $application->description }}
        </p>
    </div>
</a>
