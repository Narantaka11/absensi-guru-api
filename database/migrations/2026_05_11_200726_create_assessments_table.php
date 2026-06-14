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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('absensi', 5, 2)->default(0);
            $table->decimal('disiplin', 5, 2)->default(0);
            $table->decimal('keterampilan', 5, 2)->default(0);
            $table->decimal('produktivitas', 5, 2)->default(0);
            $table->decimal('total', 5, 2)->default(0);
            $table->integer('month');
            $table->integer('year');
            $table->timestamps();

            $table->unique(['user_id', 'month', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
