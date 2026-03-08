<?php

namespace App\Http\Controllers\API;

use App\Models\Remise;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RemiseController extends Controller
{
    /**
     * Lister toutes les remises
     */
    public function index()
    {
        return Remise::with('commandes')->get();
    }

    /**
     * Créer une nouvelle remise
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'nullable|string|unique:remises',
            'type' => 'required|in:pourcentage,montant_fixe',
            'valeur' => 'required|numeric|min:0',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'actif' => 'boolean',
        ]);

        $remise = Remise::create($validated);
        return response()->json($remise, 201);
    }

    /**
     * Afficher une remise spécifique
     */
    public function show(Remise $remise)
    {
        return $remise->load('commandes');
    }

    /**
     * Mettre à jour une remise
     */
    public function update(Request $request, Remise $remise)
    {
        $validated = $request->validate([
            'code' => 'nullable|string|unique:remises,code,' . $remise->id,
            'type' => 'sometimes|in:pourcentage,montant_fixe',
            'valeur' => 'sometimes|numeric|min:0',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'actif' => 'boolean',
        ]);

        $remise->update($validated);
        return response()->json($remise);
    }

    /**
     * Supprimer une remise
     */
    public function destroy(Remise $remise)
    {
        $remise->delete();
        return response()->json(null, 204);
    }

    /**
     * Appliquer une remise à une commande
     */
    public function appliquer(Request $request)
    {
        $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'remise_id' => 'required|exists:remises,id',
        ]);

        $remise = Remise::findOrFail($request->remise_id);
        $commande = Commande::findOrFail($request->commande_id);

        // Vérifier si la remise est active
        if (!$remise->actif) {
            return response()->json([
                'message' => 'Cette remise n\'est pas active'
            ], 400);
        }

        // Vérifier les dates de validité
        $now = now();
        if ($remise->date_debut && $now < $remise->date_debut) {
            return response()->json([
                'message' => 'Cette remise n\'est pas encore valide'
            ], 400);
        }
        if ($remise->date_fin && $now > $remise->date_fin) {
            return response()->json([
                'message' => 'Cette remise a expiré'
            ], 400);
        }

        // Attacher la remise à la commande
        $commande->remises()->attach($remise->id);

        return response()->json([
            'message' => 'Remise appliquée avec succès',
            'commande' => $commande->load('remises')
        ]);
    }

    /**
     * Valider un code de remise
     */
    public function validerCode(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $remise = Remise::where('code', $request->code)->first();

        if (!$remise) {
            return response()->json([
                'valide' => false,
                'message' => 'Code de remise invalide'
            ], 404);
        }

        if (!$remise->actif) {
            return response()->json([
                'valide' => false,
                'message' => 'Cette remise n\'est pas active'
            ], 400);
        }

        $now = now();
        if ($remise->date_debut && $now < $remise->date_debut) {
            return response()->json([
                'valide' => false,
                'message' => 'Cette remise n\'est pas encore valide'
            ], 400);
        }
        if ($remise->date_fin && $now > $remise->date_fin) {
            return response()->json([
                'valide' => false,
                'message' => 'Cette remise a expiré'
            ], 400);
        }

        return response()->json([
            'valide' => true,
            'remise' => $remise
        ]);
    }
}