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
        Schema::create(table: 'nilais', callback: function (Blueprint $table): void {
            $table->id();
            $table->foreignId(column: 'siswa_id')->constrained(table: 'siswas')->onDelete(action: 'cascade');
            $table->float(column: 'sem_1');
            $table->float(column: 'sem_2');
            $table->float(column: 'sem_3');
            $table->float(column: 'sem_4');
            $table->float(column: 'sem_5');
            $table->float(column: 'prestasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'nilais');
    }
};
