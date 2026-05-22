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
        Schema::create('employes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employe_id')->constrained('users');
            $table->string('resume');
            $table->string('offer_letter')->nullable();
            $table->string('cnic');
            $table->string('employe_type'); 
            $table->string('profile_img')->nullable();
            $table->decimal('salary', 10, 2);
            $table->integer('target');
            $table->integer('late')->nullable();
            $table->integer('leave')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employes');
    }
};
