<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('book_customer', function (Blueprint $table) {

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete();

            $table->foreignId('book_id')
                ->constrained('books')
                ->cascadeOnDelete();

            $table->string('status')->default('wishlist');
            // wishlist | reading | read

            $table->boolean('is_favorite')->default(false);

            $table->unsignedTinyInteger('rating')->nullable();
            // 1–5

            $table->text('review')->nullable();

            $table->timestamps();

            // Un customer solo puede tener un registro por libro
            $table->unique(['customer_id', 'book_id']);

            $table->index(['customer_id', 'status']);
            $table->index('book_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_customer');
    }
};
