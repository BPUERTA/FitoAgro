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
        Schema::table('farm_work_order', function (Blueprint $table) {
            $table->string('letter', 1)->default('A')->after('farm_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farm_work_order', function (Blueprint $table) {
            $table->dropColumn('letter');
        });
    }
};
