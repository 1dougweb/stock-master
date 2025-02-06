<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // First check if we're still using the old table name
        if (Schema::hasTable('service_orders')) {
            Schema::table('service_orders', function (Blueprint $table) {
                $table->foreignId('employee_id')->nullable()->constrained()->nullOnDelete();
            });
        } else {
            Schema::table('material_requests', function (Blueprint $table) {
                $table->foreignId('employee_id')->nullable()->constrained()->nullOnDelete();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('service_orders')) {
            Schema::table('service_orders', function (Blueprint $table) {
                $table->dropForeign(['employee_id']);
                $table->dropColumn('employee_id');
            });
        } else {
            Schema::table('material_requests', function (Blueprint $table) {
                $table->dropForeign(['employee_id']);
                $table->dropColumn('employee_id');
            });
        }
    }
};
