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
        Schema::table('formations', function (Blueprint $table) {
            $table->dropColumn('nom_formateur');
            $table->foreignId('id_formateur')->after('categorie_formation')->constrained('formateurs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('formations', function (Blueprint $table) {
            $table->dropForeign(['id_formateur']);
            $table->dropColumn('id_formateur');
            $table->string('nom_formateur')->after('categorie_formation');
        });
    }
};
