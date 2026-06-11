<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_dr')->unique();
            $table->foreignId('store_requisition_id')->constrained('store_requisitions');
            $table->date('tanggal');
            $table->foreignId('dibuat_oleh')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->dateTime('approved_at')->nullable();
            $table->enum('status', ['draft', 'menunggu_approval', 'approved', 'ditolak', 'revisi', 'selesai'])->default('draft');
            $table->text('catatan')->nullable();
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('delivery_requisition_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_requisition_id')->constrained()->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barangs');
            $table->integer('qty_distribusi');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_requisition_details');
        Schema::dropIfExists('delivery_requisitions');
    }
};
