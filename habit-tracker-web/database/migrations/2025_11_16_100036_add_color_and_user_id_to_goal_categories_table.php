<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('goal_categories', function (Blueprint $table) {
            $table->string('color', 20)
                  ->default('#64748b')    // szarawy domyślny
                  ->after('name');

            $table->unsignedBigInteger('user_id')
                  ->nullable()
                  ->after('color');
            // Jeśli chcesz FK i masz tabelę users:
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('goal_categories', function (Blueprint $table) {
            // jeśli dodałeś FK, najpierw dropForeign
            // $table->dropForeign(['user_id']);
            $table->dropColumn(['color', 'user_id']);
        });
    }
};
