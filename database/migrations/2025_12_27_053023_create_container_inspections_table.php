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
        Schema::create('container_inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('container_id')->constrained()->onDelete('cascade');
            $table->string('surveyor_name');
            $table->dateTime('inspection_date');
            $table->enum('condition', ['good', 'damaged', 'rejected']);
            $table->text('inspection_notes')->nullable();
            $table->enum('action', ['proceed_to_eir', 'repair_required', 'wash_required', 'write_off'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('container_inspections');
    }
};
