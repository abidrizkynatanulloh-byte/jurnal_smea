<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('guru_piket', function (Blueprint $table) {
            if (!Schema::hasColumn('guru_piket', 'shift')) {
                $table->string('shift')->default('Pagi')->after('hari'); // Pagi / Siang
            }
            if (!Schema::hasColumn('guru_piket', 'peran_piket')) {
                $table->string('peran_piket')->default('Petugas')->after('shift'); // Petugas / Koordinator / Piket Waka
            }
            if (!Schema::hasColumn('guru_piket', 'keterangan')) {
                $table->string('keterangan')->nullable()->after('peran_piket');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guru_piket', function (Blueprint $table) {
            if (Schema::hasColumn('guru_piket', 'shift')) {
                $table->dropColumn('shift');
            }
            if (Schema::hasColumn('guru_piket', 'peran_piket')) {
                $table->dropColumn('peran_piket');
            }
            if (Schema::hasColumn('guru_piket', 'keterangan')) {
                $table->dropColumn('keterangan');
            }
        });
    }
};
