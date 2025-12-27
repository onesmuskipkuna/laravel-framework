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
        Schema::create('container_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('container_id')->constrained()->onDelete('cascade');
            $table->string('photo_path');
            $table->enum('photo_type', ['before_repair', 'after_repair', 'damage', 'general']);
            $table->text('description')->nullable();
            $table->dateTime('taken_at');
            $table->boolean('sent_to_shipping_line')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('container_photos');
    }
};
