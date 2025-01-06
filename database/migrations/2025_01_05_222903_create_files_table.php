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
        Schema::create('files', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('path');
            $table->string('type');
            $table->unsignedBigInteger('size');
            $table->string('owner_type');
            $table->uuid('owner_id');
            $table->json('metadata')->nullable();
            $table->string('language');
            $table->timestamps();
            $table->softDeletes();
            $table->index([ 'owner_type', 'owner_id' ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
