<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTaskIdToTaskCommentsTable extends Migration
{
    public function up()
    {
        Schema::table('task_comments', function (Blueprint $table) {
            $table->unsignedBigInteger('task_id'); // Define la columna task_id
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade'); // Agrega una clave foránea
        });
    }

    public function down()
    {
        Schema::table('task_comments', function (Blueprint $table) {
            $table->dropForeign(['task_id']); // Elimina la clave foránea
            $table->dropColumn('task_id'); // Elimina la columna task_id
        });
    }
}
