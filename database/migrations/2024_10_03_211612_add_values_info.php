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
        Schema::table('stocks', function (Blueprint $table) {
            $table->decimal('current_value', 8, 2)->default('0.00');
            $table->decimal('high_value', 8, 2)->default('0.00');
            $table->decimal('low_value', 8, 2)->default('0.00');
            $table->decimal('last_close_value', 8, 2)->default('0.00');
            $table->timestamp('last_update_values')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropColumn([
                'current_value',
                'high_value',
                'low_value',
                'last_close_value',
                'last_update_values',
            ]);
        });
    }
};
