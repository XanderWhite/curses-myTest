<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddAvatarToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Добавляем поле avatar типа BLOB для хранения изображений
            $table->binary('avatar')->nullable(); // Для хранения BLOB
        });
        
        DB::statement('ALTER TABLE users MODIFY avatar MEDIUMBLOB NULL');
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Удаляем поле avatar, если миграция будет откатана
            $table->dropColumn('avatar');
        });
    }
}
