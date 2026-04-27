<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('goal_attachments', function (Blueprint $table) {
            $table->id();

            // Do jakiego celu należy załącznik
            $table->unsignedBigInteger('goal_id');

            // Opcjonalnie: do jakiego dnia celu (goal_days.id)
            $table->unsignedBigInteger('goal_day_id')->nullable();

            // Kto dodał plik
            $table->unsignedBigInteger('user_id');

            // Ścieżka pliku w storage
            $table->string('file_path');

            // Typ/MIME – np. image/png, video/mp4
            $table->string('mime_type', 50);

            // Oryginalna nazwa pliku
            $table->string('original_name');

            $table->timestamps();

            // Klucze obce
            $table->foreign('goal_id')
                ->references('id')->on('goals')
                ->onDelete('cascade');

            $table->foreign('goal_day_id')
                ->references('id')->on('goal_days')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goal_attachments');
    }
};
