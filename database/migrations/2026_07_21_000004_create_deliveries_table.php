<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('delivery_number')->unique();
            $table->foreignId('order_id')->constrained()->restrictOnDelete();
            $table->date('delivery_date');
            $table->string('driver_name')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->string('delivery_note_number')->nullable();
            $table->string('product_name');
            $table->integer('quantity');
            $table->string('status');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
