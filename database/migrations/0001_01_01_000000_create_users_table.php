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

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phoneNumber');
            $table->string('email')->unique();
            $table->string('userType');
            $table->string('pfp')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('donations', function (Blueprint $table) {
            $table->id('donationId');
            $table->string("itemName");
            $table->string("timeOfPreparation");
            $table->integer("quantity");
            $table->string("address");
            $table->boolean("utensilsNeeded");
            $table->string("imageUrl")->nullable();
            $table->string('charity');
            $table->foreignId("user_id")->constrained()->onDelete("cascade");
            $table->date("DateDonated");
            $table->timestamps();
        });

        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ngoId')->constrained('users', 'id')->onDelete("cascade");
            $table->foreignId('donationId')->constrained('donations', 'donationId')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists("donations");
        Schema::dropIfExists("requests");
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
