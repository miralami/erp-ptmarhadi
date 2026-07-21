<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->renameColumn('quantity', 'unit');
            $table->decimal('kubikasi', 15, 2)->nullable()->after('price');
            $table->integer('max_slot')->nullable()->after('kubikasi');
            $table->decimal('police_fee', 15, 2)->default(0)->after('max_slot');
            $table->boolean('threshold_exceeded')->default(false)->after('police_fee');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->renameColumn('unit', 'quantity');
            $table->dropColumn(['kubikasi', 'max_slot', 'police_fee', 'threshold_exceeded']);
        });
    }
};
