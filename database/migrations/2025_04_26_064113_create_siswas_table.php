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
        Schema::create(table: 'siswas', callback: function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string(column: 'nama');
            $table->foreignId(column: 'kelas_id')->constrained(table: 'kelas')->onDelete(action: 'cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'siswas');
    }
};
