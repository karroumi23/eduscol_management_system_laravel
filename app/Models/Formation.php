<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    use HasFactory;

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

    public function formateur()
    {
        return $this->belongsTo(Formateur::class, 'id_formateur');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }
}
