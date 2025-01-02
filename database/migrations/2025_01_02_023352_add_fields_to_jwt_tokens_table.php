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
        Schema::table('jwt_tokens', function (Blueprint $table) {
            $table->enum('status', [ 'valid', 'invalid' ])->after('jti');
            $table->string('expiry_time')->after('status');
            $table->string('type_time')->after('expiry_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jwt_tokens', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('expiry_time');
            $table->dropColumn('type_time');
        });
    }
};
