<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('returs', function (Blueprint $table) {
            $table->foreignId('receiving_report_id')->nullable()->change();
            $table->foreignId('purchase_order_id')->nullable()->constrained('purchase_orders')->after('receiving_report_id');
        });
    }

    public function down(): void
    {
        Schema::table('returs', function (Blueprint $table) {
            $table->dropForeign(['purchase_order_id']);
            $table->dropColumn('purchase_order_id');
            $table->foreignId('receiving_report_id')->nullable(false)->change();
        });
    }
};
