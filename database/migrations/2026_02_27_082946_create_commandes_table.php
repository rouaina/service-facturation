<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
    $table->id();
    $table->string('numero_commande')->unique();
    $table->decimal('montant',10,2);
    $table->decimal('taxe',10,2)->default(0);
    $table->decimal('remise',10,2)->default(0);
    $table->string('statut')->default('en_attente');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
