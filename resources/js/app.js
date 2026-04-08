import './bootstrap';

const searchInput = document.querySelector('#global-search');
const searchResults = document.querySelector('#search-results');
const searchFeedback = document.querySelector('#search-feedback');
const allAppsCaption = document.querySelector('#all-apps-caption');

function iconTemplate(icon) {
    const icons = {
        bank: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5 12 5l9 5.5M5 10v8m14-8v8M3 19h18M9 19v-6h6v6" /></svg>',
        chart: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 19h16M7 16l3-3 3 2 5-6" /></svg>',
        shield: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7 3v5c0 4.2-2.6 8-7 10-4.4-2-7-5.8-7-10V6l7-3Z" /></svg>',
        document: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8 3h6l5 5v13H8a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Zm5 1v5h5" /></svg>',
        users: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16 19v-1a4 4 0 0 0-8 0v1M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm8 8v-1a3 3 0 0 0-3-3M4 19v-1a3 3 0 0 1 3-3" /></svg>',
        support: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M18 10a6 6 0 1 0-12 0v4a2 2 0 0 0 2 2h2l2 3 2-3h2a2 2 0 0 0 2-2v-4Z" /></svg>',
        box: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m12 3 8 4.5-8 4.5-8-4.5L12 3Zm8 4.5V16.5L12 21l-8-4.5V7.5M12 12v9" /></svg>',
        book: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6.5A2.5 2.5 0 0 1 6.5 4H20v14H6.5A2.5 2.5 0 0 0 4 20.5v-14Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M8 4v14" /></svg>',
    };

    return icons[icon] ?? icons.document;
}

function accentClass(accentColor) {
    const map = {
        brand: 'bg-brand-600',
        emerald: 'bg-emerald-600',
        teal: 'bg-teal-700',
        lime: 'bg-lime-600',
        sky: 'bg-sky-600',
        slate: 'bg-slate-700',
        amber: 'bg-amber-500',
        rose: 'bg-rose-600',
        orange: 'bg-orange-500',
        violet: 'bg-violet-600',
    };

    return map[accentColor] ?? map.brand;
}

function renderResult(application) {
    return `
        <a href="${application.launch_url}" class="search-result flex items-start gap-3 rounded-2xl border border-transparent px-4 py-3 hover:border-brand-100 hover:bg-brand-50/70">
            <div class="${accentClass(application.accent_color)} mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl text-white">
                ${iconTemplate(application.icon)}
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center justify-between gap-3">
                    <h4 class="truncate text-sm font-bold text-ink">${application.name}</h4>
                    <span class="shrink-0 rounded-full bg-white px-2.5 py-1 text-[11px] font-semibold text-brand-700">${application.badge ?? ''}</span>
                </div>
                <p class="mt-1 text-sm text-slate-600">${application.description ?? ''}</p>
                <p class="mt-2 text-xs text-slate-400">${application.keywords.join(' • ')}</p>
            </div>
        </a>
    `;
}

async function performSearch(term) {
    if (!searchInput || !searchResults || !searchFeedback || !allAppsCaption) {
        return;
    }

    const query = term.trim();

    if (query === '') {
        searchResults.classList.add('hidden');
        searchResults.innerHTML = '';
        searchFeedback.textContent = 'Temukan aplikasi lebih cepat dengan kata kunci.';
        allAppsCaption.textContent = 'Gunakan pencarian untuk hasil tercepat, atau pilih dari daftar berikut.';
        return;
    }

    const endpoint = new URL(searchInput.dataset.searchUrl, window.location.origin);
    endpoint.searchParams.set('q', query);

    const response = await fetch(endpoint, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            Accept: 'application/json',
        },
    });

    const payload = await response.json();
    const results = payload.data ?? [];

    searchResults.classList.remove('hidden');

    if (results.length === 0) {
        searchResults.innerHTML = `
            <div class="rounded-2xl px-4 py-5 text-center">
                <p class="text-sm font-semibold text-ink">Aplikasi tidak ditemukan</p>
                <p class="mt-1 text-sm text-slate-500">Coba gunakan nama aplikasi, fungsi, atau kata kunci lain.</p>
            </div>
        `;
        searchFeedback.textContent = 'Tidak ada hasil yang cocok.';
        allAppsCaption.textContent = `Pencarian aktif untuk kata kunci "${query}".`;
        return;
    }

    searchResults.innerHTML = results.map(renderResult).join('');
    searchFeedback.textContent = `${results.length} hasil ditemukan. Tekan Ctrl + K untuk mencari ulang kapan saja.`;
    allAppsCaption.textContent = `Pencarian aktif untuk kata kunci "${query}".`;
}

if (searchInput) {
    let timeout = null;

    searchInput.addEventListener('input', (event) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => performSearch(event.target.value), 180);
    });

    window.addEventListener('keydown', (event) => {
        if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') {
            event.preventDefault();
            searchInput.focus();
            searchInput.select();
        }
    });
}
