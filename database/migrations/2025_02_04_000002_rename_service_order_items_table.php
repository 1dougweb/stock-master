<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::rename('service_order_items', 'material_request_items');

        Schema::table('material_request_items', function (Blueprint $table) {
            $table->renameColumn('service_order_id', 'material_request_id');
        });
    }

    public function down()
    {
        Schema::table('material_request_items', function (Blueprint $table) {
            $table->renameColumn('material_request_id', 'service_order_id');
        });

        Schema::rename('material_request_items', 'service_order_items');
    }
};
