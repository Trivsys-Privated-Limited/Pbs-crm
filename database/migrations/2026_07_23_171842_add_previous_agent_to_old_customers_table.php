<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('old_customers', function (Blueprint $table) {
            $table->string('previous_agent_name')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('old_customers', function (Blueprint $table) {
            $table->dropColumn('previous_agent_name');
        });
    }
};
