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
        Schema::create('dossiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_id')->constrained('participants')->onDelete('cascade');
            $table->string('photo')->nullable();
            $table->string('nom');
            $table->string('cin');
            $table->string('telephone');
            $table->date('date_naissance');
            $table->string('permis');
            $table->enum('categorie_formation', ['TRM', 'TRV']);
            $table->enum('type_formation', ['FQIMO', 'FCO']);
            $table->string('nom_formateur');
            $table->decimal('prix_formation', 10, 2);
            $table->decimal('acompte', 10, 2)->default(0);
            $table->date('date_depart_formation');
            $table->date('date_fin_formation');
            $table->foreignId('cree_par')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dossiers');
    }
};
