<x-filament-panels::page>
    <div class="space-y-6">

        {{-- ============ TOP STAT BADGES ============ --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($stats as $stat)
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br {{ $stat['gradient'] }} p-5 text-white shadow-lg">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/80">{{ $stat['label'] }}</p>
                            <p class="mt-2 text-2xl font-bold tracking-tight">{{ $stat['value'] }}</p>
                        </div>
                        <div class="rounded-xl bg-white/15 p-2">
                            <x-filament::icon :icon="$stat['icon']" class="h-6 w-6 text-white" />
                        </div>
                    </div>
                    <div class="pointer-events-none absolute -bottom-6 -right-6 h-24 w-24 rounded-full bg-white/10"></div>
                </div>
            @endforeach
        </div>

        {{-- ============ CHARTS ROW ============ --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Dossiers per month --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 lg:col-span-2 dark:bg-gray-900 dark:ring-white/10">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-base font-semibold text-gray-950 dark:text-white">Dossiers créés (6 derniers mois)</h3>
                </div>
                <canvas id="dossiersChart" height="110"></canvas>
            </div>

            {{-- Répartition catégorie --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <h3 class="mb-4 text-base font-semibold text-gray-950 dark:text-white">Répartition par catégorie</h3>
                <canvas id="categorieChart" height="180"></canvas>
                <div class="mt-4 flex justify-center gap-6 text-sm">
                    <span class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                        <span class="h-2.5 w-2.5 rounded-full" style="background-color:#2F6FA5"></span> TRM
                    </span>
                    <span class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                        <span class="h-2.5 w-2.5 rounded-full" style="background-color:#F4C300"></span> TRV
                    </span>
                </div>
            </div>
        </div>

        {{-- ============ RECENT ACTIVITY ROW ============ --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            {{-- Recent dossiers --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <h3 class="mb-4 text-base font-semibold text-gray-950 dark:text-white">Derniers dossiers</h3>
                <div class="divide-y divide-gray-100 dark:divide-white/5">
                    @forelse ($recentDossiers as $dossier)
                        <div class="flex items-center justify-between py-3">
                            <div>
                                <p class="text-sm font-medium text-gray-950 dark:text-white">
                                    {{ $dossier->participant?->nom ?? '—' }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $dossier->type_formation }} · {{ $dossier->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <span class="rounded-full px-2.5 py-1 text-xs font-medium
                                {{ $dossier->categorie_formation === 'TRM' ? 'bg-[#2F6FA5]/10 text-[#2F6FA5]' : 'bg-[#F4C300]/20 text-[#8a6d00]' }}">
                                {{ $dossier->categorie_formation }}
                            </span>
                        </div>
                    @empty
                        <p class="py-3 text-sm text-gray-500 dark:text-gray-400">Aucun dossier pour le moment.</p>
                    @endforelse
                </div>
            </div>

            {{-- Recent participants --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <h3 class="mb-4 text-base font-semibold text-gray-950 dark:text-white">Derniers participants</h3>
                <div class="divide-y divide-gray-100 dark:divide-white/5">
                    @forelse ($recentParticipants as $participant)
                        <div class="flex items-center gap-3 py-3">
                            @if ($participant->photo)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($participant->photo) }}" class="h-9 w-9 rounded-full object-cover" alt="{{ $participant->nom }}">
                            @else
                                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-[#2F6FA5]/10 text-sm font-semibold text-[#2F6FA5]">
                                    {{ strtoupper(substr($participant->nom, 0, 1)) }}
                                </div>
                            @endif
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-950 dark:text-white">{{ $participant->nom }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $participant->num_permis }}</p>
                            </div>
                            <span class="text-xs text-gray-400">{{ $participant->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <p class="py-3 text-sm text-gray-500 dark:text-gray-400">Aucun participant pour le moment.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- ============ CHART.JS ============ --}}
    @once
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    @endonce

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const monthlyLabels = @json($monthlyDossiers['labels']);
            const monthlyData = @json($monthlyDossiers['data']);
            const categorieData = @json($categorieRepartition);

            const dossiersCtx = document.getElementById('dossiersChart');
            if (dossiersCtx) {
                new Chart(dossiersCtx, {
                    type: 'line',
                    data: {
                        labels: monthlyLabels,
                        datasets: [{
                            label: 'Dossiers',
                            data: monthlyData,
                            fill: true,
                            tension: 0.4,
                            borderColor: '#2F6FA5',
                            backgroundColor: 'rgba(47, 111, 165, 0.12)',
                            pointBackgroundColor: '#F4C300',
                            pointRadius: 4,
                        }],
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { precision: 0 } },
                        },
                    },
                });
            }

            const categorieCtx = document.getElementById('categorieChart');
            if (categorieCtx) {
                new Chart(categorieCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['TRM', 'TRV'],
                        datasets: [{
                            data: [categorieData.TRM ?? 0, categorieData.TRV ?? 0],
                            backgroundColor: ['#2F6FA5', '#F4C300'],
                            borderWidth: 0,
                        }],
                    },
                    options: {
                        responsive: true,
                        cutout: '70%',
                        plugins: { legend: { display: false } },
                    },
                });
            }
        });
    </script>
</x-filament-panels::page>