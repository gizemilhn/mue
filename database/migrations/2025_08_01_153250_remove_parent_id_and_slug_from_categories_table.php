<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            // Önce foreign key constraint’i kaldır
            $table->dropForeign(['parent_id']);

            // Sonra sütunu kaldır
            $table->dropColumn('parent_id');
        });
    }

    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('id');

            $table->foreign('parent_id')
                ->references('id')->on('categories')
                ->onDelete('cascade');
        });
    }
};
