<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // For MySQL, modifying an enum requires either changing the column type or using raw DB queries.
        // Let's change the status column to varchar/enum to allow 'dikirim'.
        // We will also add the shipping columns.
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->string('status', 50)->default('draft')->change();
            $table->string('nomor_resi')->nullable()->after('status');
            $table->string('kurir_ekspedisi')->nullable()->after('nomor_resi');
            $table->dateTime('tanggal_pengiriman')->nullable()->after('kurir_ekspedisi');
            $table->text('catatan_pengiriman')->nullable()->after('tanggal_pengiriman');
        });
    }

    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn(['nomor_resi', 'kurir_ekspedisi', 'tanggal_pengiriman', 'catatan_pengiriman']);
        });
    }
};
