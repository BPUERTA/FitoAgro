<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_group_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_group_id')->constrained('client_groups')->cascadeOnDelete();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->decimal('percentage', 5, 2);
            $table->timestamps();

            $table->unique(['client_group_id', 'client_id'], 'client_group_members_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_group_members');
    }
};
