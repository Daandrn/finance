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
        Schema::table('titles', function (Blueprint $table) {
            $table->string('tax', 5)->nullable(false)->change();
            $table->string('value_buy', 21)->nullable(false)->change();
            $table->string('value_current', 21)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('titles', function (Blueprint $table) {
            $table->string('tax', 6)->nullable(false)->change();
            $table->string('value_buy', 12)->nullable(false)->change();
            $table->string('value_current', 12)->nullable(false)->change();
        });
    }
};
