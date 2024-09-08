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
        Schema::create('stocks', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('ticker', 6)->nullable(false)->unique();
            $table->string('name', 50)->nullable(true);
            $table->smallInteger('stocks_types_id')->nullable(false);

            $table->foreign('stocks_types_id')->references('id')->on('stocks_types')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
