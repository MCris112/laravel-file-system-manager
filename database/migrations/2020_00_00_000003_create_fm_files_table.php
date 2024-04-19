<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create( 'fm_files', function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('fm_folder_id')->nullable();
            $table->string('name');
            $table->string('path_filename');
            $table->string('path_folder');
            $table->string('disk');
            $table->integer('size');
            $table->string('size_type');
            $table->string('type');
            $table->string('mimetype');
            $table->string('extension');

            $table->boolean('is_public')->default(false);

            $table->unsignedBigInteger('parent_id')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('fm_files')->cascadeOnDelete()->cascadeOnDelete();
            $table->foreign('fm_folder_id')->references('id')->on('fm_folders')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
        });
    }

};
