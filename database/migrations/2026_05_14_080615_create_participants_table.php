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
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->string('photo')->nullable();
            $table->string('nom');
            $table->string('cin')->unique();
            $table->string('telephone');
            $table->string('num_permis')->unique();
            $table->enum('categorie_formation', ['TRM', 'TRV']);
            $table->foreignId('cree_par')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
