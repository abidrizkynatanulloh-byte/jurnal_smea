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
        if (Schema::hasTable('izin_guru') && !Schema::hasColumn('izin_guru', 'status_piket')) {
            Schema::table('izin_guru', function (Blueprint $table) {
                $table->enum('status_piket', ['Menunggu', 'Disetujui', 'Ditolak'])->default('Menunggu')->after('status_waka');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('izin_guru') && Schema::hasColumn('izin_guru', 'status_piket')) {
            Schema::table('izin_guru', function (Blueprint $table) {
                $table->dropColumn('status_piket');
            });
        }
    }
};
