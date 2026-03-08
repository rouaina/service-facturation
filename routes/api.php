<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CommandeController;
use App\Http\Controllers\API\PaiementController;
use App\Http\Controllers\API\FactureController;
use App\Http\Controllers\API\FinanceController;


use App\Http\Controllers\API\RemiseController;
use App\Http\Controllers\API\RemboursementController;
use App\Http\Controllers\API\AbonnementController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// LOG AU TOUT DÉBUT
error_log("=== DÉMARRAGE DE L'APPLICATION ===");
error_log("Heure: " . date('Y-m-d H:i:s'));
error_log("Fichier: " . __FILE__);


Route::prefix('commandes')->group(function () {
    Route::post('/', [CommandeController::class, 'creer']);       // POST /api/commandes
    Route::get('/', [CommandeController::class, 'lister']);       // GET  /api/commandes
    Route::get('{id}', [CommandeController::class, 'details']);   // GET  /api/commandes/{id}
});

Route::prefix('paiements')->group(function () {
    Route::post('payer', [PaiementController::class, 'payer']); // POST /api/paiements/payer
    Route::get('lister', [PaiementController::class, 'lister']); // GET  /api/paiements/lister
});

Route::prefix('factures')->group(function () {
    Route::get('{id}/generer', [FactureController::class, 'generer']); // GET  /api/factures/{id}/generer
    Route::get('/', [FactureController::class, 'index']);               // GET  /api/factures
    Route::post('/', [FactureController::class, 'store']);              // POST /api/factures
});
Route::get('/finance/statistiques', [FinanceController::class,'statistiques']);


Route::get('/paiement-form', function () {
    return view('paiement'); // 'paiement' = resources/views/paiement.blade.php
});




// Remises
Route::prefix('remises')->group(function () {
    Route::get('/', [App\Http\Controllers\API\RemiseController::class, 'index']);
    Route::post('/', [App\Http\Controllers\API\RemiseController::class, 'store']);
    Route::get('{remise}', [App\Http\Controllers\API\RemiseController::class, 'show']);
    Route::put('{remise}', [App\Http\Controllers\API\RemiseController::class, 'update']);
    Route::delete('{remise}', [App\Http\Controllers\API\RemiseController::class, 'destroy']);
    Route::post('appliquer', [App\Http\Controllers\API\RemiseController::class, 'appliquer']);
    Route::post('valider-code', [App\Http\Controllers\API\RemiseController::class, 'validerCode']);
});

// Remboursements
Route::prefix('remboursements')->group(function () {
    Route::get('/', [App\Http\Controllers\API\RemboursementController::class, 'index']);
    Route::post('/', [App\Http\Controllers\API\RemboursementController::class, 'store']);
    Route::get('{remboursement}', [App\Http\Controllers\API\RemboursementController::class, 'show']);
    Route::put('{remboursement}', [App\Http\Controllers\API\RemboursementController::class, 'update']);
    Route::delete('{remboursement}', [App\Http\Controllers\API\RemboursementController::class, 'destroy']);
});

// Abonnements
Route::prefix('abonnements')->group(function () {
    Route::get('/', [App\Http\Controllers\API\AbonnementController::class, 'index']);
    Route::post('/', [App\Http\Controllers\API\AbonnementController::class, 'store']);
    Route::get('{abonnement}', [App\Http\Controllers\API\AbonnementController::class, 'show']);
    Route::put('{abonnement}', [App\Http\Controllers\API\AbonnementController::class, 'update']);
    Route::delete('{abonnement}', [App\Http\Controllers\API\AbonnementController::class, 'destroy']);
    Route::post('{abonnement}/renouveler', [App\Http\Controllers\API\AbonnementController::class, 'renouveler']);
    Route::post('{abonnement}/generer-commande', [App\Http\Controllers\API\AbonnementController::class, 'genererCommande']);
});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


