<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'photo',
        'nom',
        'cin',
        'telephone',
        'num_permis',
        'categorie_formation',
        'cree_par',
    ];

    public function dossiers()
    {
        return $this->hasMany(Dossier::class, 'participant_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }
}