<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{

    public function up(): void
    {

        if(config('filesystemmanager.user.avatar'))
        Schema::table( 'users', function (Blueprint $table){
            $table->string('avatar_disk');
            $table->integer('avatar_size');
        });
    }

    public function down(): void
    {
        if(config('filesystemmanager.user.avatar'))
        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn(['avatar_disk', 'avatar_size']);
        });
    }
};
