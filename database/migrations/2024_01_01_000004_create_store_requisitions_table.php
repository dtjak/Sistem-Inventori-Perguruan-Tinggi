<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_sr')->unique();
            $table->date('tanggal');
            $table->string('unit_peminjam');
            $table->foreignId('pemohon_id')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->dateTime('approved_at')->nullable();
            $table->enum('status', ['draft', 'menunggu_approval', 'disetujui', 'ditolak', 'revisi'])->default('draft');
            $table->text('catatan')->nullable();
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('store_requisition_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_requisition_id')->constrained()->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barangs');
            $table->integer('qty');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_requisition_details');
        Schema::dropIfExists('store_requisitions');
    }
};
