<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'photo',
        'nom',
        'cin',
        'telephone',
        'num_permis',
        'categorie_formation',
        'cree_par',
    ];

    // ── Relationships ──────────────────────────────────────────────

    public function dossiers()
    {
        return $this->hasMany(Dossier::class, 'participant_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    // ── Scopes ─────────────────────────────────────────────────────

    public function scopeDuMois($query)
    {
        return $query->whereBetween('created_at', [
            now()->startOfMonth(),
            now()->endOfMonth(),
        ]);
    }
}
