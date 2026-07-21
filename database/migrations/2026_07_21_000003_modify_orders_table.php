<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'product_name',
                'quantity',
                'price',
                'po_number',
                'delivery_note_number',
                'invoice_number',
            ]);
            $table->renameColumn('date', 'order_date');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('order_date', 'date');
            $table->string('product_name')->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->string('po_number')->nullable();
            $table->string('delivery_note_number')->nullable();
            $table->string('invoice_number')->nullable();
        });
    }
};
