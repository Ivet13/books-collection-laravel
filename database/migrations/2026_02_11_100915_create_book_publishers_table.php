<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('book_publishers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();
            $table->foreignId('publisher_id')->constrained('publishers')->restrictOnDelete();

            $table->unsignedSmallInteger('published_year')->nullable();

            $table->timestamps();

            // Si cada libro solo puede tener 1 publisher (lo más habitual):
            // $table->unique('book_id');

            $table->index('publisher_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_publishers');
    }
};
