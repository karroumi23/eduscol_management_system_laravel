<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Formation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'titre',
        'type_formation',
        'categorie_formation',
        'id_formateur',
        'prix',
        'date_depart',
        'date_fin',
        'cree_par',
    ];

    protected $casts = [
        'date_depart' => 'date',
        'date_fin' => 'date',
        'prix' => 'decimal:2',
    ];

    // ── Relationships ──────────────────────────────────────────────

    public function formateur()
    {
        return $this->belongsTo(Formateur::class, 'id_formateur');
    }

    public function dossiers()
    {
        return $this->hasMany(Dossier::class, 'formation_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    // ── Scopes ─────────────────────────────────────────────────────

    public function scopeActives($query)
    {
        return $query->where('date_fin', '>=', now()->toDateString());
    }

    public function scopeDuMois($query)
    {
        return $query->whereBetween('date_depart', [
            now()->startOfMonth(),
            now()->endOfMonth(),
        ]);
    }
}
