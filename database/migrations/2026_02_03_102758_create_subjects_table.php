<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', static function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedInteger('units');

            $table->foreignId('locale_id')
                ->nullable()
                ->constrained('locales')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->timestamps();

            $table->unique(['title', 'locale_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
