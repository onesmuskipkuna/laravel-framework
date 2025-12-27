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
        Schema::create('interchange_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('container_id')->constrained()->onDelete('cascade');
            $table->foreignId('gate_entry_id')->constrained()->onDelete('cascade');
            $table->string('document_number')->unique();
            $table->dateTime('issue_date');
            $table->text('recipient_details');
            $table->boolean('copy_sent_to_shipping_line')->default(false);
            $table->dateTime('sent_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interchange_documents');
    }
};
