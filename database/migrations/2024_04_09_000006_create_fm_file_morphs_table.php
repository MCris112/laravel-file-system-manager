<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create( 'fm_file_morphs', function (Blueprint $table){
            $table->string('field');
            $table->string('fileable_type');
            $table->string('fileable_id');
            $table->unsignedBigInteger('fm_file_id');

            $table->foreign('fm_file_id')->references('id')->on('fm_files');
        });
    }

};
