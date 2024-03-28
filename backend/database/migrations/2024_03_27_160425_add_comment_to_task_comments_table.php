<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommentToTaskCommentsTable extends Migration
{
    public function up()
    {
        Schema::table('task_comments', function (Blueprint $table) {
            $table->text('comment'); // Define la columna comment
        });
    }

    public function down()
    {
        Schema::table('task_comments', function (Blueprint $table) {
            $table->dropColumn('comment'); // Elimina la columna comment
        });
    }
}
