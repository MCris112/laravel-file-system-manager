<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create( 'fm_folders', function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('disk');
            $table->string('name');


            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('fm_folders');
        });
    }

};
