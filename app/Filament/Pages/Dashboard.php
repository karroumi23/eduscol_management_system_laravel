<?php

namespace App\Filament\Pages;

use App\Models\Dossier;
use App\Models\Participant;
use Carbon\Carbon;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Tableau de bord';

    protected static ?string $title = 'Tableau de bord';

    // Makes this page the "/" (home) page of the admin panel.
    protected static ?string $slug = '/';

    protected static ?int $navigationSort = -2;

    protected string $view = 'filament.pages.dashboard';

    /** @var array<int, array<string, mixed>> */
    public array $stats = [];

    /** @var array{labels: array<int, string>, data: array<int, int>} */
    public array $monthlyDossiers = [];

    /** @var array<string, int> */
    public array $categorieRepartition = [];

    public $recentDossiers;

    public $recentParticipants;

    public function mount(): void
    {
        $this->stats = $this->buildStats();
        $this->monthlyDossiers = $this->buildMonthlyDossiers();
        $this->categorieRepartition = $this->buildCategorieRepartition();

        $this->recentDossiers = Dossier::with('participant')
            ->latest()
            ->limit(5)
            ->get();

        $this->recentParticipants = Participant::latest()
            ->limit(5)
            ->get();
    }

    protected function buildStats(): array
    {
        $totalParticipants = Participant::count();
        $totalDossiers = Dossier::count();
        $totalRevenue = (float) Dossier::sum('prix_formation');
        $totalAcompte = (float) Dossier::sum('acompte');
        $totalRestant = $totalRevenue - $totalAcompte;

        return [
            [
                'label' => 'Participants',
                'value' => number_format($totalParticipants),
                'icon' => 'heroicon-o-users',
                'gradient' => 'from-[#2F6FA5] to-[#1E4E7D]',
            ],
            [
                'label' => 'Dossiers',
                'value' => number_format($totalDossiers),
                'icon' => 'heroicon-o-folder-open',
                'gradient' => 'from-[#F4C300] to-[#D9A600]',
            ],
            [
                'label' => 'Revenu total',
                'value' => number_format($totalRevenue, 2).' MAD',
                'icon' => 'heroicon-o-banknotes',
                'gradient' => 'from-emerald-500 to-emerald-700',
            ],
            [
                'label' => 'Reste à payer',
                'value' => number_format($totalRestant, 2).' MAD',
                'icon' => 'heroicon-o-exclamation-circle',
                'gradient' => 'from-rose-500 to-rose-700',
            ],
        ];
    }

    protected function buildMonthlyDossiers(): array
    {
        $start = now()->subMonths(5)->startOfMonth();

        $dossiers = Dossier::where('created_at', '>=', $start)->get(['created_at']);

        $grouped = $dossiers->groupBy(fn ($dossier) => $dossier->created_at->format('Y-m'));

        $months = collect(range(5, 0))->map(fn ($i) => now()->subMonths($i)->format('Y-m'));

        return [
            'labels' => $months->map(fn ($month) => Carbon::createFromFormat('Y-m', $month)->translatedFormat('M Y'))->values()->toArray(),
            'data' => $months->map(fn ($month) => $grouped->get($month, collect())->count())->values()->toArray(),
        ];
    }

    protected function buildCategorieRepartition(): array
    {
        return [
            'TRM' => Participant::where('categorie_formation', 'TRM')->count(),
            'TRV' => Participant::where('categorie_formation', 'TRV')->count(),
        ];
    }
}