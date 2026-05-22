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
        Schema::create('customers', function (Blueprint $table) {
              $table->id();
              $table->string('customer_name');
              $table->string('customer_email')->default('No Email');
              $table->string('customer_number')->unique();
              $table->decimal('price', 8, 2);
              $table->text('remarks');
              $table->string('status');
              $table->string('a_name');
              $table->string('regitr_date')->nullable();
              $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
