@if (! session('password_changed') && (session('status') || session('warning') || session('error')))
    <div 
        x-data="{ show: true }" 
        x-show="show" 
        x-init="setTimeout(() => show = false, 5000)"
        class="fixed bottom-8 right-8 z-50 max-w-md animate-in fade-in slide-in-from-bottom-4 duration-300"
    >
        <div class="glass-panel overflow-hidden rounded-[24px] border @if(session('warning')) border-amber-500/30 bg-amber-500/5 @elseif(session('error')) border-rose-500/30 bg-rose-500/5 @else border-brand-500/30 bg-brand-500/5 @endif p-1 shadow-soft">
            <div class="flex items-center gap-4 rounded-[20px] px-5 py-4">
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl @if(session('warning')) bg-amber-500/20 text-amber-200 @elseif(session('error')) bg-rose-500/20 text-rose-200 @else bg-brand-500/20 text-brand-200 @endif">
                    @if(session('warning'))
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    @elseif(session('error'))
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                    @else
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @endif
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-white">
                        {{ session('status') ?: (session('warning') ?: session('error')) }}
                    </p>
                </div>
                <button @click="show = false" class="text-slate-400 hover:text-white transition">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
@endif
