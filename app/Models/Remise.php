<?php

namespace App\Models;

use App\Models\Commande;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remise extends Model
{
    use HasFactory;

      protected $fillable = [
        'code',
        'type',              // 'pourcentage', 'montant_fixe'
        'valeur',
        'date_debut',
        'date_fin',
        'actif',
        // ajoutez d'autres champs selon votre table
    ];

    // Relation avec les commandes (si une remise peut être associée à plusieurs commandes)
    public function commandes()
    {
        return $this->belongsToMany(Commande::class);
    }
}
