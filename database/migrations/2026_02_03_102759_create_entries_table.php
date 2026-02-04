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
            $table->text('statement')->nullable();
            $table->text('solution')->nullable();
            $table->unsignedInteger('unit')->nullable();

            $table->foreignId('subject_id')
                ->nullable()
                ->constrained('subjects')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('locale_id')
                ->nullable()
                ->constrained('locales')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->timestamps();

            $table->unique(['subject_id', 'unit', 'number', 'locale_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entries');
    }
};