<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('store_requisition_details', function (Blueprint $table) {
            $table->foreignId('barang_id')->nullable()->change();
            $table->foreignId('aset_id')->nullable()->after('barang_id')->constrained('asets')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('store_requisition_details', function (Blueprint $table) {
            $table->dropForeign(['aset_id']);
            $table->dropColumn('aset_id');
            $table->foreignId('barang_id')->nullable(false)->change();
        });
    }
};
