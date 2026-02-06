<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('farms', function (Blueprint $table) {
            $table->unsignedBigInteger('organization_id')->nullable()->after('id');
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('farms', function (Blueprint $table) {
            if (Schema::hasColumn('farms', 'organization_id')) {
                $table->dropForeign(['organization_id']);
                $table->dropColumn('organization_id');
            }
        });
    }
};
