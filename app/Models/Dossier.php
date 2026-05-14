<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dossier extends Model
{
    use HasFactory;

    protected $fillable = [
        'participant_id',
        'photo',
        'nom',
        'cin',
        'telephone',
        'date_naissance',
        'permis',
        'categorie_formation',
        'type_formation',
        'nom_formateur',
        'prix_formation',
        'acompte',
        'date_depart_formation',
        'date_fin_formation',
        'cree_par',
    ];

    public function participant()
    {
        return $this->belongsTo(Participant::class, 'participant_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }
}