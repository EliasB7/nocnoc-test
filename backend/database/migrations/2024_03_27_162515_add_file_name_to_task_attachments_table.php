<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileNameToTaskAttachmentsTable extends Migration
{
    public function up()
    {
        Schema::table('task_attachments', function (Blueprint $table) {
            $table->string('file_name')->after('user_id');
        });
    }

    public function down()
    {
        Schema::table('task_attachments', function (Blueprint $table) {
            $table->dropColumn('file_name');
        });
    }
}
