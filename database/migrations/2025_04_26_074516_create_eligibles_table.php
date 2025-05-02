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
        Schema::create(table: 'eligibles', callback: function (Blueprint $table): void {
            $table->id();
            $table->foreignId(column: 'siswa_id')->constrained(table: 'siswas')->onDelete(action: 'cascade');
            $table->decimal('hasil_akhir', 10, 6);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'eligibles');
    }
};
