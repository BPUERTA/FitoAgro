<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('work_orders', 'code')) {
                $table->string('code')->nullable()->after('organization_id');
            }
            if (!Schema::hasColumn('work_orders', 'priority')) {
                $table->string('priority', 10)->default('medium')->after('description');
            }
            if (!Schema::hasColumn('work_orders', 'scheduled_start_at')) {
                $table->dateTime('scheduled_start_at')->nullable()->after('priority');
            }
            if (!Schema::hasColumn('work_orders', 'scheduled_end_at')) {
                $table->dateTime('scheduled_end_at')->nullable()->after('scheduled_start_at');
            }
            if (!Schema::hasColumn('work_orders', 'created_by')) {
                $table->string('created_by')->nullable()->after('status');
            }
            if (!Schema::hasColumn('work_orders', 'closed_by')) {
                $table->string('closed_by')->nullable()->after('created_by');
            }
            if (!Schema::hasColumn('work_orders', 'canceled_by')) {
                $table->string('canceled_by')->nullable()->after('closed_by');
            }
            if (!Schema::hasColumn('work_orders', 'canceled_reason')) {
                $table->text('canceled_reason')->nullable()->after('canceled_by');
            }
        });

        if (Schema::hasColumn('work_orders', 'number') && Schema::hasColumn('work_orders', 'code')) {
            DB::table('work_orders')
                ->whereNull('code')
                ->update(['code' => DB::raw('number')]);
        }

        if (Schema::hasColumn('work_orders', 'status')) {
            DB::statement("UPDATE work_orders SET status = 'abierto' WHERE status = 'en_proceso'");
            DB::statement("UPDATE work_orders SET status = 'cerrado' WHERE status = 'completada'");
            DB::statement("UPDATE work_orders SET status = 'cancelado' WHERE status = 'cancelada'");
            DB::statement("ALTER TABLE work_orders MODIFY status VARCHAR(20) NOT NULL DEFAULT 'pendiente'");
        }

        Schema::table('work_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('work_orders', 'code')) {
                return;
            }
            $table->unique(['organization_id', 'code'], 'work_orders_org_code_unique');
        });
    }

    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropUnique('work_orders_org_code_unique');
            $columns = [
                'code',
                'priority',
                'scheduled_start_at',
                'scheduled_end_at',
                'created_by',
                'closed_by',
                'canceled_by',
                'canceled_reason',
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('work_orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
