<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RemiseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

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
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //

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

   
    }

    /**
     * Remove the specified resource from storage.
     */
      public function destroy(Remise $remise)
    {
        $remise->delete();
        return response()->json(null, 204);
    }
}
