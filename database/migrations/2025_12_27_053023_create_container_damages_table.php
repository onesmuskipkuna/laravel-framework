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
        Schema::create('container_damages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('container_id')->constrained()->onDelete('cascade');
            $table->foreignId('damage_code_id')->constrained()->onDelete('cascade');
            $table->foreignId('inspection_id')->constrained('container_inspections')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->decimal('repair_cost', 10, 2)->nullable();
            $table->enum('status', ['pending', 'in_repair', 'repaired'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('container_damages');
    }
};
