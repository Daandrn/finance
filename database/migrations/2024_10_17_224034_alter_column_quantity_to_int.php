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
        Schema::table('user_stocks', function (Blueprint $table) {
            $table->unsignedInteger('quantity')->nullable(false)->change();
        });

        Schema::table('user_stocks_movements', function (Blueprint $table) {
            $table->unsignedInteger('quantity')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_stocks', function (Blueprint $table) {
            $table->decimal('quantity', 14, 2)->nullable(false)->change();
        });

        Schema::table('user_stocks_movements', function (Blueprint $table) {
            $table->decimal('quantity', 11, 2)->nullable(false)->change();
        });
    }
};
