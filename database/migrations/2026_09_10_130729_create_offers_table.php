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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('supplier_id')->constrained()->restrictOnDelete();
            $table->foreignId('property_id')->constrained()->restrictOnDelete();
            $table->foreignId('import_id')->constrained('imports')->restrictOnDelete();
            $table->string('external_id');
            $table->date('check_in');
            $table->date('check_out');
            $table->unsignedSmallInteger('max_guests');
            $table->unsignedBigInteger('price');
            $table->char('currency', 3);
            $table->unsignedInteger('available_units');
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
