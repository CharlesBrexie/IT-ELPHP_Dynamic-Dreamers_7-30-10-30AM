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
        Schema::create('donations', function (Blueprint $table) {
            $table->donationId()->primary();
            $table->foreignId('user_Id')->nullable()->index();
            $table->string('item_name');
            $table->string('address');
            $table->string('timeOfPreparation');
            $table->integer('quantity');
            $table->boolean('utensils_required');
            $table->string('charity');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
