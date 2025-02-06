<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::rename('service_orders', 'material_requests');
        Schema::rename('service_order_products', 'material_request_products');
    }

    public function down()
    {
        Schema::rename('material_requests', 'service_orders');
        Schema::rename('material_request_products', 'service_order_products');
    }
};
