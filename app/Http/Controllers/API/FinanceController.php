<?php

namespace App\Http\Controllers\API;
use App\Models\Paiement;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    //

    

    public function statistiques()
    {
        return [
            'chiffre_affaire'=>Paiement::sum('montant'),
            'transactions'=>Paiement::count()
        ];
    }
}
