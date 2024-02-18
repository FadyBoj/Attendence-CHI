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
        Schema::create('active_lectures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('doctor_id', 36);
            $table->char('uniqueId', 36);
            $table->timestamp('expireDate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('active_lectures');
    }
};
