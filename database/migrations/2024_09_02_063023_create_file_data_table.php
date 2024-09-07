<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileDataTable extends Migration
{
    public function up()
    {
        Schema::create('file_data', function (Blueprint $table) {
            $table->id();
            $table->string('success')->nullable();
            $table->string('failed')->nullable();
            $table->decimal('gmv', 15, 2)->nullable();
            $table->decimal('profit', 15, 2)->nullable();
            $table->decimal('babe', 15, 2)->nullable();
            $table->decimal('net_profit', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('file_data');
    }
}
