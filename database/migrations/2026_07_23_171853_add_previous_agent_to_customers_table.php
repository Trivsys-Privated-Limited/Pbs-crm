<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Column already exists check - agar nahi hai toh add karo
            if (!Schema::hasColumn('customers', 'previous_agent_name')) {
                $table->string('previous_agent_name')->nullable()->after('user_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'previous_agent_name')) {
                $table->dropColumn('previous_agent_name');
            }
        });
    }
};
