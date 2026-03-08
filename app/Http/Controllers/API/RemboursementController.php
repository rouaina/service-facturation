<?php

namespace App\Http\Controllers\API;

use App\Models\Remboursement;
use App\Models\Paiement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RemboursementController extends Controller
{
    /**
     * Lister tous les remboursements
     */
    public function index()
    {
        return Remboursement::with('paiement.commande')->get();
    }

    /**
     * Créer une demande de remboursement
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'paiement_id' => 'required|exists:paiements,id',
            'montant' => 'required|numeric|min:0',
            'motif' => 'nullable|string',
        ]);

        $paiement = Paiement::findOrFail($validated['paiement_id']);

        // Vérifier que le montant du remboursement ne dépasse pas le paiement
        if ($validated['montant'] > $paiement->montant) {
            return response()->json([
                'message' => 'Le montant du remboursement ne peut pas dépasser le montant du paiement'
            ], 400);
        }

        // Vérifier que le paiement n'a pas déjà été remboursé
        $totalRembourse = Remboursement::where('paiement_id', $paiement->id)
            ->where('statut', '!=', 'refusé')
            ->sum('montant');

        if (($totalRembourse + $validated['montant']) > $paiement->montant) {
            return response()->json([
                'message' => 'Le montant total des remboursements dépasse le montant du paiement'
            ], 400);
        }

        $validated['statut'] = 'en_attente';
        $remboursement = Remboursement::create($validated);
        
        return response()->json($remboursement->load('paiement'), 201);
    }

    /**
     * Afficher un remboursement spécifique
     */
    public function show(Remboursement $remboursement)
    {
        return $remboursement->load('paiement.commande');
    }

    /**
     * Mettre à jour le statut d'un remboursement
     */
    public function update(Request $request, Remboursement $remboursement)
    {
        $validated = $request->validate([
            'statut' => 'sometimes|in:en_attente,approuvé,refusé,effectué',
            'date_remboursement' => 'nullable|date',
        ]);

        // Si le statut passe à "effectué", enregistrer la date
        if (isset($validated['statut']) && $validated['statut'] === 'effectué') {
            $validated['date_remboursement'] = now();
        }

        $remboursement->update($validated);
        return response()->json($remboursement->load('paiement'));
    }

    /**
     * Supprimer une demande de remboursement
     */
    public function destroy(Remboursement $remboursement)
    {
        $remboursement->delete();
        return response()->json(null, 204);
    }
}