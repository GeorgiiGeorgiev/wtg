<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('import_history', function (Blueprint $table) {
            $table->unsignedBigInteger('import_id');
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('offer_id');
            $table->unsignedBigInteger('property_id');
            $table->timestamp('created_at')->useCurrent();
            $table->primary(['import_id', 'offer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_history');
    }
};
