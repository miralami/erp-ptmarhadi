<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('orders')
            ->where('status', 'DELIVERY_SCHEDULED')
            ->update(['status' => 'SCHEDULED']);

        DB::table('orders')
            ->where('status', 'DELIVERED')
            ->update(['status' => 'IN_TRANSIT']);

        DB::table('orders')
            ->where('status', 'DELIVERY_NOTE_RETURNED')
            ->update(['status' => 'COMPLETED']);

        DB::table('orders')
            ->where('status', 'WAITING_PO')
            ->update(['status' => 'COMPLETED']);
    }

    public function down(): void
    {
    }
};
