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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id');
            $table->string('serial')->unique();
            $table->string('batch')->nullable();
            $table->integer('warranty_months')->nullable();
            $table->foreignId('recharge_group_id')->nullable();
            $table->foreignId('customer_id')->nullable();
            $table->dateTime('registered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
