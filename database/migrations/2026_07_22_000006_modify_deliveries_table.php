<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropColumn(['delivery_note_number', 'product_name', 'quantity']);

            $table->foreignId('vehicle_id')->nullable()->constrained()->nullOnDelete()->after('order_id');
            $table->string('vehicle_plate_manual')->nullable()->after('vehicle_id');
            $table->string('vehicle_type_manual')->nullable()->after('vehicle_plate_manual');
            $table->decimal('uang_jalan', 15, 2)->default(0)->after('driver_name');
            $table->json('photo_muat')->nullable()->after('status');
            $table->json('photo_bongkar')->nullable()->after('photo_muat');
            $table->json('photo_surat_jalan')->nullable()->after('photo_bongkar');
        });
    }

    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropColumn([
                'vehicle_id', 'vehicle_plate_manual', 'vehicle_type_manual',
                'uang_jalan', 'photo_muat', 'photo_bongkar', 'photo_surat_jalan',
            ]);

            $table->string('delivery_note_number')->nullable();
            $table->string('product_name');
            $table->integer('quantity');
        });
    }
};
