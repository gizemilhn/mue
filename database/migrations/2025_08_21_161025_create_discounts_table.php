<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // İndirim adı (örnek: "Black Friday %20")
            $table->string('type'); // 'percentage' veya 'fixed'
            $table->decimal('value', 8, 2); // İndirim miktarı (%20 veya 10.00 TL)
            $table->unsignedBigInteger('product_id')->nullable(); // Hangi ürüne uygulanacak
            $table->unsignedBigInteger('category_id')->nullable(); // Hangi kategoriye uygulanacak
            $table->timestamp('start_date')->nullable(); // İndirimin başlangıç tarihi
            $table->timestamp('end_date')->nullable(); // İndirimin bitiş tarihi
            $table->timestamps();

            // İlişkiler
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
