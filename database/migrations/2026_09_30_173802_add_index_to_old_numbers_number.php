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
    DB::statement('ALTER TABLE old_numbers ADD INDEX old_numbers_number_idx (number(20))');
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    DB::statement('ALTER TABLE old_numbers DROP INDEX old_numbers_number_idx');
}
};
