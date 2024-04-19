<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{

    protected $types = [
        'int',
        'varchar',
        'datetime',
        'decimal'
    ];

    public function up(): void
    {
        Schema::create( 'fm_metadata', function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('fm_file_id')->nullable();

            $table->string('attr');

            $table->string('value');

            $table->foreign('fm_file_id')->references('id')->on('fm_files');
        });
//
//        foreach ($this->types as $type)
//        {
//            Schema::create( 'fm_metadata_'.$type, function (Blueprint $table) use ($type){
//                $table->id();
//                $table->unsignedBigInteger('fm_file_id')->nullable();
//
//                $table->string('attr');
//
//                switch ($type)
//                {
//                    case 'int':
//                        $table->integer('value');
//                        break;
//                    case 'datetime':
//                        $table->dateTime('value');
//                        break;
//                    case 'decimal':
//                        $table->decimal('value');
//                        break;
//                    default:
//                        $table->string('value');
//                }
//
//
//                $table->foreign('fm_file_id')->references('id')->on('fm_files');
//            });
//        }
    }

};
