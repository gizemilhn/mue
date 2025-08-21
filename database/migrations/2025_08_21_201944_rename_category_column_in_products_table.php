<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // "category" sütununu "category_id" olarak yeniden adlandır
            $table->renameColumn('category', 'category_id');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {

            $table->renameColumn('category_id', 'category');
        });
    }
};
