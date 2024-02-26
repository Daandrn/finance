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
        Schema::create('titles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->notNullable();
            $table->string('title')->notNullable();
            $table->string('tax')->notNullable();
            $table->bigInteger('modality')->notNullable();
            $table->date('date_buy')->notNullable();
            $table->date('date_liquidity')->notNullable();
            $table->date('date_due')->notNullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict')->notNullable();
            $table->foreign('modality')->references('id')->on('modalities')->onUpdate('cascade')->onDelete('restrict')->notNullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('titles');
    }
};
