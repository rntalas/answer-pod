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

            $table->foreignId('unit_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->integer('number');

            $table->tinyInteger('statement');
            $table->tinyInteger('solution');

            $table->timestamps();

            $table->unique(['unit_id', 'number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entries');
    }
};
