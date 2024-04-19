<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create( 'fm_metadata', function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('fm_file_id')->nullable();

            $table->string('attr');

            $table->string('value');

            $table->foreign('fm_file_id')->references('id')->on('fm_files');
        });
    }

};
