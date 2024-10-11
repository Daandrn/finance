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
        Schema::create('splits', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('stocks_id')->nullable(false);
            $table->time('date')->nullable(false);
            $table->decimal('quantity', 8, 2)->nullable(false);
            $table->timestamps();

            $table->foreign('stocks_id')->references('id')->on('stocks')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('splits');
    }
};
