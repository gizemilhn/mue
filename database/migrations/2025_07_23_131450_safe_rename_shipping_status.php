<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Önce yeni sütunu ekleyelim
        Schema::table('shippings', function (Blueprint $table) {
            if (!Schema::hasColumn('shippings', 'status')) {
                $table->string('status')->nullable()->after('shipping_status');
            }
        });

        // 2. Verileri eski sütundan yeniye kopyalayalım
        DB::table('shippings')->update(['status' => DB::raw('shipping_status')]);

        // 3. Eski sütunu silelim
        Schema::table('shippings', function (Blueprint $table) {
            if (Schema::hasColumn('shippings', 'shipping_status')) {
                $table->dropColumn('shipping_status');
            }
        });

        // 4. Varsayılan değeri ayarlayalım
        DB::statement("ALTER TABLE shippings MODIFY COLUMN status VARCHAR(255) DEFAULT 'preparing'");
    }

    public function down()
    {
        // Geri alma işlemi
        Schema::table('shippings', function (Blueprint $table) {
            if (!Schema::hasColumn('shippings', 'shipping_status')) {
                $table->string('shipping_status')->nullable()->after('status');
            }
        });

        DB::table('shippings')->update(['shipping_status' => DB::raw('status')]);

        Schema::table('shippings', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
