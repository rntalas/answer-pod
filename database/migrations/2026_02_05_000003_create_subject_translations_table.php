<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subject_translations', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')
                ->constrained('subjects')
                ->cascadeOnDelete();
            $table->foreignId('locale_id')
                ->constrained('locales')
                ->cascadeOnDelete();
            $table->string('name');
            $table->string('description')
                ->nullable();
            $table->timestamps();

            $table->unique(['subject_id', 'locale_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subject_translations');
    }
};
