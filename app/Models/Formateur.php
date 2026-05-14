<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formateur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'email',
        'cree_par',
    ];

    public function getNomCompletAttribute(): string
    {
        return "{$this->prenom} {$this->nom}";
    }

    public function formations()
    {
        return $this->hasMany(Formation::class, 'id_formateur');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }
}
