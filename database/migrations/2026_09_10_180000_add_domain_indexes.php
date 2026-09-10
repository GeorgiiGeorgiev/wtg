<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->unique('uuid');
            $table->unique('code');
        });

        Schema::table('properties', function (Blueprint $table) {
            $table->unique('uuid');
            $table->unique('code');
            $table->index('city');
        });

        Schema::table('imports', function (Blueprint $table) {
            $table->unique('uuid');
        });

        Schema::table('offers', function (Blueprint $table) {
            $table->unique('uuid');
            $table->index(
                ['check_in', 'check_out', 'property_id', 'price'],
                'offers_search_index',
            );
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->unique('uuid');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
        });

        Schema::table('offers', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->dropIndex('offers_search_index');
        });

        Schema::table('imports', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
        });

        Schema::table('properties', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->dropUnique(['code']);
            $table->dropIndex(['city']);
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->dropUnique(['code']);
        });
    }
};
