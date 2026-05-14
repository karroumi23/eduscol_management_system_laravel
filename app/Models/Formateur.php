<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Formateur extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'email',
        'cree_par',
    ];

    // ── Accessors ──────────────────────────────────────────────────

    public function getNomCompletAttribute(): string
    {
        return "{$this->prenom} {$this->nom}";
    }

    // ── Relationships ──────────────────────────────────────────────

    public function formations()
    {
        return $this->hasMany(Formation::class, 'id_formateur');
    }

    public function dossiers()
    {
        return $this->hasMany(Dossier::class, 'id_formateur');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }
}
