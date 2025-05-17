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
        Schema::create('change_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // yang request (wakel)
            $table->string('model_type'); // contoh: 'Nilai' atau 'Siswa'
            $table->unsignedBigInteger('model_id');
            $table->string('action'); // 'edit' atau 'delete'
            $table->json('data')->nullable(); // data perubahan untuk edit
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null'); // gurubk yg approve
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            // Optional index agar pencarian lebih cepat
            $table->index(['model_type', 'model_id']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('change_requests');
    }
};
