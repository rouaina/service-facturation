<?php

namespace App\Http\Controllers\API;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Commande;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// /**
//  * @OA\Tag(
//  *     name="Factures",
//  *     description="Gestion des factures"
//  * )
//  */
class FactureController extends Controller
{
    // /**
    //  * @OA\Get(
    //  *     path="/api/factures/{id}/generer",
    //  *     operationId="genererFacture",
    //  *     tags={"Factures"},
    //  *     summary="Générer une facture PDF",
    //  *     description="Génère et télécharge une facture au format PDF pour une commande",
    //  *     @OA\Parameter(
    //  *         name="id",
    //  *         in="path",
    //  *         required=true,
    //  *         description="ID de la commande",
    //  *         @OA\Schema(type="integer", example=1)
    //  *     ),
    //  *     @OA\Response(
    //  *         response=200,
    //  *         description="Facture générée avec succès",
    //  *         @OA\MediaType(
    //  *             mediaType="application/pdf",
    //  *             @OA\Schema(type="string", format="binary")
    //  *         )
    //  *     ),
    //  *     @OA\Response(
    //  *         response=404,
    //  *         description="Commande non trouvée",
    //  *         @OA\JsonContent(
    //  *             @OA\Property(property="message", type="string", example="Commande non trouvée")
    //  *         )
    //  *     )
    //  * )
    //  */
    public function generer($id)
    {
        $commande = Commande::findOrFail($id);

        $pdf = Pdf::loadView('facture', compact('commande'));

        $dossier = storage_path('app/factures');
        if (!file_exists($dossier)) mkdir($dossier, 0775, true);

        $chemin = $dossier . '/facture_' . $commande->numero_commande . '_' . time() . '.pdf';
        $pdf->save($chemin);

        return response()->download($chemin, 'facture_' . $commande->numero_commande . '.pdf');
    }

    // /**
    //  * @OA\Get(
    //  *     path="/api/factures",
    //  *     operationId="listeFactures",
    //  *     tags={"Factures"},
    //  *     summary="Lister toutes les factures",
    //  *     description="Retourne la liste de toutes les commandes/factures",
    //  *     @OA\Response(
    //  *         response=200,
    //  *         description="Liste des factures",
    //  *         @OA\JsonContent(
    //  *             type="array",
    //  *             @OA\Items(
    //  *                 @OA\Property(property="id", type="integer", example=1),
    //  *                 @OA\Property(property="numero_commande", type="string", example="CMD-ABC123"),
    //  *                 @OA\Property(property="montant", type="number", example=10000),
    //  *                 @OA\Property(property="statut", type="string", example="en_attente"),
    //  *                 @OA\Property(property="created_at", type="string", format="date-time"),
    //  *                 @OA\Property(property="updated_at", type="string", format="date-time")
    //  *             )
    //  *         )
    //  *     )
    //  * )
    //  */
    public function index()
    {
        return response()->json(Commande::all());
    }

    // /**
    //  * @OA\Post(
    //  *     path="/api/factures",
    //  *     operationId="creerFacture",
    //  *     tags={"Factures"},
    //  *     summary="Créer une facture",
    //  *     description="Crée une nouvelle commande/facture",
    //  *     @OA\RequestBody(
    //  *         required=true,
    //  *         @OA\JsonContent(
    //  *             required={"numero_commande","montant"},
    //  *             @OA\Property(
    //  *                 property="numero_commande",
    //  *                 type="string",
    //  *                 example="CMD-12345",
    //  *                 description="Numéro unique de la commande"
    //  *             ),
    //  *             @OA\Property(
    //  *                 property="montant",
    //  *                 type="number",
    //  *                 example=10000,
    //  *                 description="Montant de la commande"
    //  *             )
    //  *         )
    //  *     ),
    //  *     @OA\Response(
    //  *         response=201,
    //  *         description="Facture créée avec succès",
    //  *         @OA\JsonContent(
    //  *             @OA\Property(property="id", type="integer", example=1),
    //  *             @OA\Property(property="numero_commande", type="string", example="CMD-12345"),
    //  *             @OA\Property(property="montant", type="number", example=10000),
    //  *             @OA\Property(property="statut", type="string", example="en_attente"),
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
    //  *         response=500,
    //  *         description="Erreur serveur"
    //  *     )
    //  * )
    //  */
    public function store(Request $request)
    {
        $request->validate([
            'numero_commande' => 'required|string',
            'montant' => 'required|numeric|min:0'
        ]);

        $commande = Commande::create([
            'numero_commande' => $request->numero_commande,
            'montant' => $request->montant,
            'statut' => 'en_attente'
        ]);

        // Génération du PDF
        $pdf = Pdf::loadView('facture', compact('commande'));
        $dossier = storage_path('app/factures');
        if (!file_exists($dossier)) mkdir($dossier, 0775, true);
        $chemin = $dossier . '/facture_' . $commande->numero_commande . '_' . time() . '.pdf';
        $pdf->save($chemin);

        return response()->json($commande, 201);
    }
}