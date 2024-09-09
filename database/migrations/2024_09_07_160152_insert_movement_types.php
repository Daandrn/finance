<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $types = [
            ['description' => 'Compra'],
            ['description' => 'Venda'],
        ];

        DB::table('stocks_movement_types')->insert($types);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
