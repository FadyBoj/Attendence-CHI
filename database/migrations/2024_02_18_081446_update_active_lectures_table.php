<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('active_lectures', function (Blueprint $table) {
            $table->dropColumn('expireDate');
        });

        Schema::table('active_lectures', function (Blueprint $table) {
            $table->timestamp('expireDate')->after('uniqueId');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};