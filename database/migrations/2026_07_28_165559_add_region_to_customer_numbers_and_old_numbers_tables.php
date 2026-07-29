<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. customer_numbers table mein column add karna
        Schema::table('customer_numbers', function (Blueprint $table) {
            $table->string('region')->nullable()->after('id'); // Ya kisi bhi column ke baad
        });

        // 2. old_numbers table mein column add karna
        Schema::table('old_numbers', function (Blueprint $table) {
            $table->string('region')->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('customer_numbers', function (Blueprint $table) {
            $table->dropColumn('region');
        });

        Schema::table('old_numbers', function (Blueprint $table) {
            $table->dropColumn('region');
        });
    }
};
