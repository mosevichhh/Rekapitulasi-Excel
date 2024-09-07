<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFileDataTable extends Migration
{
    public function up()
    {
        Schema::table('file_data', function (Blueprint $table) {
            $table->string('new_column')->nullable();  // Gantilah dengan kolom yang kamu butuhkan
        });
    }

    public function down()
    {
        Schema::table('file_data', function (Blueprint $table) {
            $table->dropColumn('new_column');  // Gantilah dengan kolom yang sesuai
        });
    }
}
