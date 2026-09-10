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
        Schema::create('imports', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('supplier_id')->constrained()->restrictOnDelete();
            $table->string('external_import_id');
            $table->timestamp('sent_at');
            $table->string('status', 20)->default('pending');
            $table->unsignedInteger('total_offers')->default(0);
            $table->unsignedInteger('processed_offers')->default(0);
            $table->json('payload');
            $table->text('error')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imports');
    }
};
