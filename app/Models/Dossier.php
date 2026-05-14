<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dossier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'participant_id',
        'formation_id',
        'photo',
        'nom',
        'cin',
        'telephone',
        'date_naissance',
        'permis',
        'categorie_formation',
        'type_formation',
        'id_formateur',
        'prix_formation',
        'acompte',
        'date_depart_formation',
        'date_fin_formation',
        'cree_par',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_depart_formation' => 'date',
        'date_fin_formation' => 'date',
        'prix_formation' => 'decimal:2',
        'acompte' => 'decimal:2',
    ];

    // ── Relationships ──────────────────────────────────────────────

    public function participant()
    {
        return $this->belongsTo(Participant::class, 'participant_id');
    }

    public function formation()
    {
        return $this->belongsTo(Formation::class, 'formation_id');
    }

    public function formateur()
    {
        return $this->belongsTo(Formateur::class, 'id_formateur');
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'dossier_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    // ── Accessors ──────────────────────────────────────────────────

    public function getSoldeAttribute(): float
    {
        $totalPaye = $this->paiements()->sum('montant');

        return (float) $this->prix_formation - (float) $totalPaye;
    }

    public function getStatutPaiementAttribute(): string
    {
        $totalPaye = $this->paiements()->sum('montant');
        $prix = (float) $this->prix_formation;

        if ($totalPaye <= 0) {
            return 'impaye';
        }

        if ((float) $totalPaye >= $prix) {
            return 'paye';
        }

        return 'partiel';
    }

    // ── Scopes ─────────────────────────────────────────────────────

    public function scopeNonPayees($query)
    {
        return $query->whereHas('paiements', function ($q) {
            $q->selectRaw('SUM(montant)')->havingRaw('SUM(montant) < dossiers.prix_formation');
        })->orWhereDoesntHave('paiements');
    }

    public function scopeDuMois($query)
    {
        return $query->whereBetween('created_at', [
            now()->startOfMonth(),
            now()->endOfMonth(),
        ]);
    }

    public function scopeActives($query)
    {
        return $query->where('date_fin_formation', '>=', now()->toDateString());
    }
}
