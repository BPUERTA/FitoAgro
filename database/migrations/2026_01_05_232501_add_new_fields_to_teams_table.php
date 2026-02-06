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
        Schema::table('teams', function (Blueprint $table) {
            $table->string('servicio')->nullable()->after('name');
            $table->string('tipo_servicio')->nullable()->after('servicio');
            $table->string('dominio')->nullable()->after('tipo_servicio');
            $table->string('matricula')->nullable()->after('dominio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn(['servicio', 'tipo_servicio', 'dominio', 'matricula']);
        });
    }
};
