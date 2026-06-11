<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nip')->nullable()->after('name');
            $table->string('unit')->nullable()->after('nip');
            $table->string('telepon')->nullable()->after('unit');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->after('telepon');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nip', 'unit', 'telepon', 'status', 'deleted_at']);
        });
    }
};
