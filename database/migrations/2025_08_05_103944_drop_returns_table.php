<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropReturnsTable extends Migration
{
    /**
     * Tabloyu silmek için migration
     */
    public function up()
    {
        Schema::dropIfExists('returns');
    }

    /**
     * Geri alma (rollback) durumu için
     */
    public function down()
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->string('return_code')->nullable();
            $table->string('cargo_company')->nullable();
            $table->timestamps();
        });
    }
}
