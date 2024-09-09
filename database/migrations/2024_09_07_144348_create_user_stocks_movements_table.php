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
        Schema::create('user_stocks_movements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->smallInteger('stocks_id')->nullable(false);
            $table->smallInteger('movement_type_id')->nullable(false);
            $table->decimal('quantity', 11)->nullable(false);
            $table->decimal('value', 8, 2)->nullable(false);
            $table->timestamp('date')->nullable(false);
            $table->timestamps();

            $table->foreign('stocks_id')->references('id')->on('stocks')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('movement_type_id')->references('id')->on('movement_types')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_stocks_movements');
    }
};
