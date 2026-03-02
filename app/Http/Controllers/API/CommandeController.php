<?php

namespace App\Http\Controllers\API;

use App\Models\Commande;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    /**
     * =========================
     * CREER UNE COMMANDE
     * =========================
     */

     /**
      * @OA\Post(
      *     path="/api/commandes",
      *     tags={"Commandes"},
      *     summary="Créer une commande",
      *     description="Créer une nouvelle commande",
      *     @OA\RequestBody(
      *         required=true,
      *         @OA\JsonContent(
      *             required={"montant"},
      *             @OA\Property(property="montant", type="number", example=10000)
      *         )
      *     ),
      *     @OA\Response(
      *         response=201,
      *         description="Commande créée avec succès"
      *     ),
      *     @OA\Response(
      *         response=400,
      *         description="Données invalides"
      *     )
      * )
     */
    public function creer(Request $request)
    {
        $request->validate([
            'montant' => 'required|numeric|min:0'
        ]);

        $commande = Commande::create([
            'numero_commande' => 'CMD-' . Str::random(6),
            'montant' => $request->montant,
            'statut' => 'en_attente'
        ]);

        return response()->json($commande, 201);
    }

    /**
     * =========================
     * LISTER LES COMMANDES
     * =========================
     */

     /**
     * @OA\Get(
      *     path="/api/commandes",
      *     tags={"Commandes"},
      *     summary="Lister toutes les commandes",
      *     @OA\Response(
      *         response=200,
      *         description="Liste des commandes"
      *     )
      * )
      */
    public function lister()
    {
        return response()->json(Commande::all());
    }

    /**
     * =========================
     * DETAILS COMMANDE
     * =========================
     */

    // /**
    //  * @OA\Get(
    //  *     path="/api/commandes/{id}",
    //  *     tags={"Commandes"},
    //  *     summary="Détails d'une commande",
    //  *     @OA\Parameter(
    //  *         name="id",
    //  *         in="path",
    //  *         required=true,
    //  *         description="ID de la commande",
    //  *         @OA\Schema(type="integer", example=2)
    //  *     ),
    //  *     @OA\Response(
    //  *         response=200,
    //  *         description="Commande trouvée"
    //  *     ),
    //  *     @OA\Response(
    //  *         response=404,
    //  *         description="Commande non trouvée"
    //  *     )
    //  * )
    //  */
    public function details($id)
    {
        $commande = Commande::find($id);

        if (!$commande) {
            return response()->json([
                'message' => 'Commande non trouvée'
            ], 404);
        }

        return response()->json($commande);
    }
}