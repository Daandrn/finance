<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $lastMovementType = DB::table('movement_types')->aggregate('max', ['id']);
        if ($lastMovementType) {
            DB::statement("SELECT setval('movement_types_id_seq', $lastMovementType, true)");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
