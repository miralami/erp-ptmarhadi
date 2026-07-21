<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('customer_po_number')->nullable()->after('customer_id');
            $table->string('customer_spb_number')->nullable()->after('customer_po_number');
            $table->decimal('subtotal', 15, 2)->default(0)->after('invoice_total');
            $table->decimal('ppn_rate', 5, 2)->default(1.1)->after('subtotal');
            $table->decimal('ppn_amount', 15, 2)->default(0)->after('ppn_rate');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'customer_po_number',
                'customer_spb_number',
                'subtotal',
                'ppn_rate',
                'ppn_amount',
            ]);
        });
    }
};
