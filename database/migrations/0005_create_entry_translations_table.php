<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entry_translations', static function (Blueprint $table) {
            $table->id();

            $table->foreignId('entry_id')
                ->constrained('entries')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('locale_id')
                ->constrained('locales')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->text('statement')->nullable();
            $table->text('solution')->nullable();

            $table->timestamps();

            $table->unique(['entry_id', 'locale_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entry_translations');
    }
};
