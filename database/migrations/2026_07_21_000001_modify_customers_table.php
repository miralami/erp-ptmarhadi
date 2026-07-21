<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->renameColumn('name', 'company_name');
            $table->string('contact_person')->nullable()->after('company_name');
            $table->text('notes')->nullable()->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->renameColumn('company_name', 'name');
            $table->dropColumn(['contact_person', 'notes']);
        });
    }
};
