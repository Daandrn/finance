<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement(<<<SQL
            DELETE FROM splits;
        SQL);

        DB::statement(<<<SQL
            ALTER TABLE splits DROP COLUMN date;
        SQL);

        Schema::table('splits', function (Blueprint $table) {
            $table->addColumn('date', 'date')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
