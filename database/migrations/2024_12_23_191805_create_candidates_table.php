<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable()->index();
            $table->string('years_of_experience');
            $table->string('phone_number_1')->unique();
            $table->string('phone_number_2')->nullable()->unique();
            $table->string('whatsapp_number')->unique();
            $table->string('email_1')->unique();
            $table->string('email_2')->nullable()->unique();
            $table->jsonb('generated_platform_cv')->nullable();
            $table->jsonb('social_media');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
