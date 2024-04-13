<?php

namespace MCris112\FileSystemManager\Facades;

use Illuminate\Database\Schema\Blueprint;

class FileSystemManagerTable
{

    public static function hasImage(Blueprint $table)
    {
        $table->unsignedBigInteger('fm_file_id');
        $table->foreign('fm_file_id')->references('id')->on( 'fm_files' );
    }
}
