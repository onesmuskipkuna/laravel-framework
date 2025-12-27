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
        Schema::create('storage_instructions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('container_id')->constrained()->onDelete('cascade');
            $table->foreignId('shipping_line_id')->constrained()->onDelete('cascade');
            $table->text('instructions');
            $table->string('storage_location')->nullable();
            $table->dateTime('instruction_date');
            $table->enum('instruction_source', ['email', 'booking', 'phone', 'manual'])->default('email');
            $table->boolean('is_matched')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storage_instructions');
    }
};
