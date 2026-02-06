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
        Schema::table('product_work_order', function (Blueprint $table) {
            $table->decimal('has', 10, 2)->nullable()->after('unit');
            $table->decimal('total', 10, 2)->nullable()->after('has');
            $table->string('um_total', 50)->nullable()->after('total');
            $table->string('note', 255)->nullable()->after('um_total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_work_order', function (Blueprint $table) {
            $table->dropColumn(['has', 'total', 'um_total', 'note']);
        });
    }
};
