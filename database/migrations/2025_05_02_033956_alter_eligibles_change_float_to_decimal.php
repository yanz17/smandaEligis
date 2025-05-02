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
        Schema::table('eligibles', function (Blueprint $table) {
            $table->decimal('hasil_akhir', 10, 6)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eligibles', function (Blueprint $table) {
            $table->float('hasil_akhir')->change();
        });
    }
};
