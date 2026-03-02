<?php

namespace App\Http\Controllers\API;

use App\Models\Paiement;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// /**
//  * @OA\Tag(
//  *     name="Paiements",
//  *     description="Gestion des paiements"
//  * )
//  */
class PaiementController extends Controller
{
    /**
     * =========================
     * EFFECTUER UN PAIEMENT
     * =========================
     */

    // /**
    //  * @OA\Post(
    //  *     path="/api/paiements/payer",
    //  *     operationId="effectuerPaiement",
    //  *     tags={"Paiements"},
    //  *     summary="Effectuer un paiement",
    //  *     description="Enregistre un nouveau paiement pour une commande",
    //  *     @OA\RequestBody(
    //  *         required=true,
    //  *         @OA\JsonContent(
    //  *             required={"commande_id","montant"},
    //  *             @OA\Property(
    //  *                 property="commande_id",
    //  *                 type="integer",
    //  *                 example=1,
    //  *                 description="ID de la commande à payer"
    //  *             ),
    //  *             @OA\Property(
    //  *                 property="montant",
    //  *                 type="number",
    //  *                 format="float",
    //  *                 example=5000,
    //  *                 description="Montant du paiement"
    //  *             ),
    //  *             @OA\Property(
    //  *                 property="methode",
    //  *                 type="string",
    //  *                 example="carte",
    //  *                 enum={"carte", "especes", "virement", "mobile"},
    //  *                 description="Méthode de paiement"
    //  *             )
    //  *         )
    //  *     ),
    //  *     @OA\Response(
    //  *         response=201,
    //  *         description="Paiement réussi",
    //  *         @OA\JsonContent(
    //  *             @OA\Property(property="id", type="integer", example=1),
    //  *             @OA\Property(property="commande_id", type="integer", example=1),
    //  *             @OA\Property(property="montant", type="number", example=5000),
    //  *             @OA\Property(property="methode", type="string", example="carte"),
    //  *             @OA\Property(property="transaction_id", type="string", example="5f1a2b3c4d5e6"),
    //  *             @OA\Property(property="statut", type="string", example="reussi"),
    //  *             @OA\Property(property="created_at", type="string", format="date-time"),
    //  *             @OA\Property(property="updated_at", type="string", format="date-time")
    //  *         )
    //  *     ),
    //  *     @OA\Response(
    //  *         response=400,
    //  *         description="Données invalides",
    //  *         @OA\JsonContent(
    //  *             @OA\Property(property="message", type="string", example="The given data was invalid."),
    //  *             @OA\Property(property="errors", type="object")
    //  *         )
    //  *     ),
    //  *     @OA\Response(
    //  *         response=404,
    //  *         description="Commande non trouvée",
    //  *         @OA\JsonContent(
    //  *             @OA\Property(property="message", type="string", example="La commande sélectionnée n'existe pas.")
    //  *         )
    //  *     )
    //  * )
    //  */
    public function payer(Request $request)
    {
        // Validation
        $request->validate([
            'commande_id' => 'required|integer|exists:commandes,id',
            'montant'     => 'required|numeric|min:0',
            'methode'     => 'nullable|string|in:carte,especes,virement,mobile'
        ]);

        $paiement = Paiement::create([
            'commande_id'    => $request->commande_id,
            'montant'        => $request->montant,
            'methode'        => $request->methode ?? 'carte',
            'transaction_id' => uniqid('txn_', true),
            'statut'         => 'reussi',
        ]);

        return response()->json($paiement, 201);
    }

    /**
     * =========================
     * LISTER LES PAIEMENTS
     * =========================
     */

    // /**
    //  * @OA\Get(
    //  *     path="/api/paiements/lister",
    //  *     operationId="listerPaiements",
    //  *     tags={"Paiements"},
    //  *     summary="Lister tous les paiements",
    //  *     description="Retourne la liste de tous les paiements effectués",
    //  *     @OA\Response(
    //  *         response=200,
    //  *         description="Liste des paiements",
    //  *         @OA\JsonContent(
    //  *             type="array",
    //  *             @OA\Items(
    //  *                 @OA\Property(property="id", type="integer", example=1),
    //  *                 @OA\Property(property="commande_id", type="integer", example=1),
    //  *                 @OA\Property(property="montant", type="number", example=5000),
    //  *                 @OA\Property(property="methode", type="string", example="carte"),
    //  *                 @OA\Property(property="transaction_id", type="string", example="txn_5f1a2b3c4d5e6"),
    //  *                 @OA\Property(property="statut", type="string", example="reussi"),
    //  *                 @OA\Property(property="created_at", type="string", format="date-time"),
    //  *                 @OA\Property(property="updated_at", type="string", format="date-time")
    //  *             )
    //  *         )
    //  *     )
    //  * )
    //  */
    public function lister()
    {
        $paiements = Paiement::with('commande')->get();
        return response()->json($paiements);
    }
}