<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_counters', function (Blueprint $table) {
            $table->id();
            $table->string('prefix');
            $table->string('date_ymd', 6);
            $table->integer('last_number')->default(0);
            $table->unique(['prefix', 'date_ymd']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_counters');
    }
};
