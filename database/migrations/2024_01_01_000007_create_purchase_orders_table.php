<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_po')->unique();
            $table->foreignId('purchase_requisition_id')->nullable()->constrained('purchase_requisitions');
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->date('tanggal');
            $table->date('tanggal_kirim')->nullable();
            $table->decimal('total', 15, 2)->default(0);
            $table->foreignId('dibuat_oleh')->constrained('users');
            $table->foreignId('approved_head_purchasing')->nullable()->constrained('users');
            $table->dateTime('approved_head_at')->nullable();
            $table->foreignId('approved_finance')->nullable()->constrained('users');
            $table->dateTime('approved_finance_at')->nullable();
            $table->enum('status', ['draft', 'menunggu_head_purchasing', 'menunggu_finance', 'approved', 'ditolak'])->default('draft');
            $table->text('catatan')->nullable();
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('purchase_order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barangs');
            $table->integer('qty');
            $table->decimal('harga', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_order_details');
        Schema::dropIfExists('purchase_orders');
    }
};
