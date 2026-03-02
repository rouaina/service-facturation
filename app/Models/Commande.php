<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;




/**
 * @OA\Schema(
 *     schema="Commande",
 *     title="Commande",
 *     description="Modèle d'une commande",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="numero_commande", type="string"),
 *     @OA\Property(property="montant", type="number", format="float"),
 *     @OA\Property(property="statut", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Commande extends Model
{
    use HasFactory;
     // Champs que l'on peut remplir avec create() ou update()
    protected $fillable = [
        'numero_commande',
        'montant',
        'statut'
    ];
}
