<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paiement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'dossier_id',
        'montant',
        'mode_paiement',
        'date_paiement',
        'note',
        'cree_par',
    ];

    protected $casts = [
        'date_paiement' => 'date',
        'montant' => 'decimal:2',
    ];

    // ── Relationships ──────────────────────────────────────────────

    public function dossier()
    {
        return $this->belongsTo(Dossier::class, 'dossier_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }
}
