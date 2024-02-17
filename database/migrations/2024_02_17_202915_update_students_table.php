<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Unique;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::table('students',function(Blueprint $table)
        {
            $table->dropColumn('id');
        });

        Schema::table('students',function(Blueprint $table)
        {
            $table->uuid('id')->primary()->unique()->first();
            $table->integer('college_id')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
