<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            $table->dropColumn(['photo', 'nom', 'cin', 'telephone', 'permis']);
        });
    }

    public function down(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            $table->string('photo')->nullable();
            $table->string('nom')->nullable();
            $table->string('cin')->nullable();
            $table->string('telephone')->nullable();
            $table->string('permis')->nullable();
        });
    }
};