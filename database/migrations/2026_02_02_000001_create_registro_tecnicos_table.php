<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registro_tecnicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('farm_id')->nullable()->constrained('farms')->nullOnDelete();
            $table->string('code');
            $table->string('status', 20)->default('abierto');
            $table->json('objectives')->nullable();
            $table->text('note')->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->json('photos')->nullable();
            $table->string('audio_path')->nullable();
            $table->string('created_by')->nullable();
            $table->string('closed_by')->nullable();
            $table->string('canceled_by')->nullable();
            $table->text('canceled_reason')->nullable();
            $table->timestamps();

            $table->unique(['organization_id', 'code'], 'registro_tecnicos_org_code_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registro_tecnicos');
    }
};
