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
        Schema::table('user_stocks_movements', function (Blueprint $table) {
            $table->unsignedDecimal('average_value', 8, 2)->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_stocks_movements', function (Blueprint $table) {
            $table->dropColumn('average_value');
        });
    }
};
