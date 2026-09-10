<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('imports', function (Blueprint $table) {
            $table->unique(
                ['supplier_id', 'external_import_id'],
                'imports_supplier_external_unique',
            );
        });

        Schema::table('offers', function (Blueprint $table) {
            $table->unique(
                ['supplier_id', 'external_id'],
                'offers_supplier_external_unique',
            );
        });
    }

    public function down(): void
    {
        Schema::table('imports', function (Blueprint $table) {
            $table->dropUnique('imports_supplier_external_unique');
        });

        Schema::table('offers', function (Blueprint $table) {
            $table->dropUnique('offers_supplier_external_unique');
        });
    }
};
