<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entry_images', static function (Blueprint $table) {
            $table->id();

            $table->foreignId('entry_id')
                ->constrained('entries')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('field')->nullable();
            $table->string('path')->nullable();
            $table->unsignedInteger('position')->nullable();

            $table->index(['entry_id', 'field']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entry_translations_images');
    }
};
