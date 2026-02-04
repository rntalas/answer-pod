<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entries', static function (Blueprint $table) {
            $table->id();
            $table->integer('number');
            $table->string('image')->nullable();
            $table->text('solution')->nullable();

            $table->foreignId('lesson_id')
                ->nullable()
                ->constrained('units')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('locale_id')
                ->nullable()
                ->constrained('locales')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entries');
    }
};
