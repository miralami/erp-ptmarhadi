<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('received_by')->nullable()->after('customer_id');
            $table->string('origin_company')->nullable()->after('order_date');
            $table->string('origin_city')->nullable()->after('origin_company');
            $table->string('destination_city')->nullable()->after('origin_city');
            $table->string('category')->nullable()->after('destination_city');
            $table->string('vehicle_source')->nullable()->after('category');
            $table->string('customer_po_number')->nullable()->after('vehicle_source');
            $table->string('customer_spb_number')->nullable()->after('customer_po_number');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'received_by',
                'origin_company',
                'origin_city',
                'destination_city',
                'category',
                'vehicle_source',
                'customer_po_number',
                'customer_spb_number',
            ]);
        });
    }
};
