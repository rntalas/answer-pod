<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('entry_images', static function (Blueprint $table) {
            $table->id();

            $table->foreignId('entry_id')
                ->constrained('entries')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('field');
            $table->string('path');
            $table->unsignedInteger('position')->nullable();

            $table->index(['entry_id', 'field']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entry_translations_images');
    }
};
