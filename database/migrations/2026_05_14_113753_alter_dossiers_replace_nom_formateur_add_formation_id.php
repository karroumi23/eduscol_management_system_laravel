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
        Schema::table('dossiers', function (Blueprint $table) {
            $table->dropColumn('nom_formateur');
            $table->foreignId('id_formateur')->nullable()->after('type_formation')->constrained('formateurs')->nullOnDelete();
            $table->foreignId('formation_id')->nullable()->after('participant_id')->constrained('formations')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            $table->dropForeign(['id_formateur']);
            $table->dropColumn('id_formateur');
            $table->dropForeign(['formation_id']);
            $table->dropColumn('formation_id');
            $table->string('nom_formateur')->after('type_formation');
        });
    }
};
