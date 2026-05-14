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
        Schema::create('formations', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->enum('type_formation', ['FQIMO', 'FCO']);
            $table->enum('categorie_formation', ['TRM', 'TRV']);
            $table->string('nom_formateur');
            $table->decimal('prix', 10, 2);
            $table->date('date_depart');
            $table->date('date_fin');
            $table->foreignId('cree_par')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formations');
    }
};
