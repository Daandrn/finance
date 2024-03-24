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
            $table->string('title', 255)->notNullable();
            $table->bigInteger('title_type_id')->notNullable();
            $table->bigInteger('modality_id')->notNullable();
            $table->string('tax', 6)->notNullable();
            $table->date('date_buy')->notNullable();
            $table->date('date_liquidity')->notNullable();
            $table->date('date_due')->notNullable();
            $table->string('value_buy', 12)->notNullable();
            $table->string('value_current', 12)->notNullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict')->notNullable();
            $table->foreign('title_type_id')->references('id')->on('title_types')->onUpdate('cascade')->onDelete('restrict')->notNullable();
            $table->foreign('modality_id')->references('id')->on('modalities')->onUpdate('cascade')->onDelete('restrict')->notNullable();
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
