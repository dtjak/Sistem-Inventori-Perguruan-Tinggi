<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receiving_reports', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_rr')->unique();
            $table->foreignId('purchase_order_id')->constrained('purchase_orders');
            $table->date('tanggal_terima');
            $table->foreignId('penerima_id')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->dateTime('approved_at')->nullable();
            $table->enum('status', ['draft', 'menunggu_approval', 'approved', 'ditolak'])->default('draft');
            $table->text('catatan')->nullable();
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('receiving_report_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receiving_report_id')->constrained()->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barangs');
            $table->integer('qty_dipesan');
            $table->integer('qty_diterima');
            $table->enum('kondisi', ['baik', 'rusak', 'kurang'])->default('baik');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receiving_report_details');
        Schema::dropIfExists('receiving_reports');
    }
};
