<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   
    public function up()
{
    Schema::create('rekapitulasi', function (Blueprint $table) {
        $table->id();
        $table->integer('success');
        $table->integer('failed');
        $table->decimal('gmv', 15, 2);
        $table->decimal('profit', 15, 2);
        $table->decimal('babe', 15, 2);
        $table->decimal('net_profit', 15, 2);
        $table->date('tanggal'); // Kolom tanggal untuk rekap harian
        $table->timestamps();
    });
}

};
