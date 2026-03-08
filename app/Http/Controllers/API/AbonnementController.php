<?php

namespace App\Http\Controllers\API;

use App\Models\Abonnement;
use App\Models\User;
use App\Models\Commande;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AbonnementController extends Controller
{
    /**
     * Lister tous les abonnements
     */
    public function index()
    {
        return Abonnement::with('client')->get();
    }

    /**
     * Créer un nouvel abonnement
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:users,id',
            'plan' => 'required|string',
            'montant' => 'required|numeric|min:0',
            'frequence' => 'required|in:mensuel,annuel',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'statut' => 'sometimes|in:actif,suspendu,expiré',
        ]);

        // Si date_fin non fournie, calculer selon la fréquence
        if (!isset($validated['date_fin'])) {
            $dateDebut = new \DateTime($validated['date_debut']);
            if ($validated['frequence'] === 'mensuel') {
                $validated['date_fin'] = $dateDebut->modify('+1 month')->format('Y-m-d');
            } else {
                $validated['date_fin'] = $dateDebut->modify('+1 year')->format('Y-m-d');
            }
        }

        $validated['statut'] = $validated['statut'] ?? 'actif';
        $abonnement = Abonnement::create($validated);
        
        return response()->json($abonnement->load('client'), 201);
    }

    /**
     * Afficher un abonnement spécifique
     */
    public function show(Abonnement $abonnement)
    {
        return $abonnement->load('client');
    }

    /**
     * Mettre à jour un abonnement
     */
    public function update(Request $request, Abonnement $abonnement)
    {
        $validated = $request->validate([
            'plan' => 'sometimes|string',
            'montant' => 'sometimes|numeric|min:0',
            'frequence' => 'sometimes|in:mensuel,annuel',
            'date_fin' => 'nullable|date|after:date_debut',
            'statut' => 'sometimes|in:actif,suspendu,expiré',
        ]);

        $abonnement->update($validated);
        return response()->json($abonnement->load('client'));
    }

    /**
     * Supprimer un abonnement
     */
    public function destroy(Abonnement $abonnement)
    {
        $abonnement->delete();
        return response()->json(null, 204);
    }

    /**
     * Renouveler un abonnement
     */
    public function renouveler(Abonnement $abonnement)
    {
        if ($abonnement->statut !== 'actif') {
            return response()->json([
                'message' => 'Seuls les abonnements actifs peuvent être renouvelés'
            ], 400);
        }

        $dateFin = new \DateTime($abonnement->date_fin);
        if ($abonnement->frequence === 'mensuel') {
            $nouvelleDateFin = $dateFin->modify('+1 month')->format('Y-m-d');
        } else {
            $nouvelleDateFin = $dateFin->modify('+1 year')->format('Y-m-d');
        }

        $abonnement->update(['date_fin' => $nouvelleDateFin]);

        return response()->json([
            'message' => 'Abonnement renouvelé avec succès',
            'abonnement' => $abonnement
        ]);
    }

    /**
     * Générer une commande pour l'abonnement
     */
    public function genererCommande(Abonnement $abonnement)
    {
        if ($abonnement->statut !== 'actif') {
            return response()->json([
                'message' => 'L\'abonnement n\'est pas actif'
            ], 400);
        }

        $commande = Commande::create([
            'numero_commande' => 'ABO-' . Str::random(6),
            'montant' => $abonnement->montant,
            'statut' => 'en_attente',
            'client_id' => $abonnement->client_id,
            'abonnement_id' => $abonnement->id,
        ]);

        return response()->json([
            'message' => 'Commande générée avec succès',
            'commande' => $commande
        ], 201);
    }
}