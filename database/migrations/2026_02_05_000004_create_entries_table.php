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
            $table->unsignedInteger('unit');

            $table->foreignId('subject_id')
                ->constrained('subjects')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['subject_id', 'unit', 'number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entries');
    }
};
