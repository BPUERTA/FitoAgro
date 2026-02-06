<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement('ALTER TABLE clients MODIFY cuit VARCHAR(255) NULL');
        DB::statement('ALTER TABLE clients MODIFY email VARCHAR(255) NULL');
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement('ALTER TABLE clients MODIFY cuit VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE clients MODIFY email VARCHAR(255) NOT NULL');
    }
};
