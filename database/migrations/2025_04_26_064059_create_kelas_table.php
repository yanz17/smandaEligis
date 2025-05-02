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
        Schema::create(table: 'kelas', callback: function (Blueprint $table): void {
            $table->id();
            $table->string(column: 'nama_kelas');
            $table->string(column: 'jurusan');
            $table->foreignId(column: 'user_id')->constrained(table: 'users')->onDelete(action: 'cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'kelas');
    }
};
