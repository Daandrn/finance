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
            $table->bigInteger('user_id')->nullable(false);
            $table->string('title', 255)->nullable(false);
            $table->bigInteger('title_type_id')->nullable(false);
            $table->bigInteger('modality_id')->nullable(false);
            $table->string('tax', 6)->nullable(false);
            $table->date('date_buy')->nullable(false);
            $table->date('date_liquidity')->nullable(false);
            $table->date('date_due')->nullable(false);
            $table->string('value_buy', 12)->nullable(false);
            $table->string('value_current', 12)->nullable(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('title_type_id')->references('id')->on('title_types')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('modality_id')->references('id')->on('modalities')->onUpdate('cascade')->onDelete('restrict');
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
