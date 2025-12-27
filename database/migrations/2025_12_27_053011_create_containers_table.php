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
        Schema::create('containers', function (Blueprint $table) {
            $table->id();
            $table->string('container_number')->unique();
            $table->string('type'); // 20ft, 40ft, 40HC, etc.
            $table->foreignId('shipping_line_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'inspected', 'damaged', 'repaired', 'washed', 'rejected', 'stored', 'released'])->default('pending');
            $table->text('booking_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('containers');
    }
};
