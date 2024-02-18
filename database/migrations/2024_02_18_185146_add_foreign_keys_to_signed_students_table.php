<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('signed_students', function (Blueprint $table) {
            $table->foreign(['activeLecture_id'])->references(['id'])->on('active_lectures')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('signed_students', function (Blueprint $table) {
            $table->dropForeign('signed_students_activelecture_id_foreign');
        });
    }
};
