<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('orders')
            ->where('status', 'SCHEDULED')
            ->update(['status' => 'PERJALANAN_MUAT']);

        DB::table('orders')
            ->where('status', 'IN_TRANSIT')
            ->update(['status' => 'PERJALANAN_BONGKAR']);
    }

    public function down(): void
    {
        DB::table('orders')
            ->where('status', 'PERJALANAN_MUAT')
            ->update(['status' => 'SCHEDULED']);

        DB::table('orders')
            ->where('status', 'PERJALANAN_BONGKAR')
            ->update(['status' => 'IN_TRANSIT']);
    }
};
