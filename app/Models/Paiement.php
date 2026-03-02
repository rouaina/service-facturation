<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;
    // Champs autorisés pour mass assignment
    protected $fillable = [
        'commande_id',
        'montant',
        'methode',
        'transaction_id',
        'statut',
    ];
      // Relation avec Commande
    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }
}
