<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToTaskCommentsTable extends Migration
{
    public function up()
    {
        Schema::table('task_comments', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id'); // Define la columna user_id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Agrega una clave foránea
        });
    }

    public function down()
    {
        Schema::table('task_comments', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // Elimina la clave foránea
            $table->dropColumn('user_id'); // Elimina la columna user_id
        });
    }
}
