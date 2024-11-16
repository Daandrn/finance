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
        Schema::table('splits', function (Blueprint $table) {
            $table->renameColumn('quantity', 'grouping');
        });
        
        Schema::table('splits', function (Blueprint $table) {
            $table->unsignedSmallInteger('grouping')->nullable(true)->default(0)->change();
            $table->unsignedSmallInteger('split')->nullable(true)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('splits', function (Blueprint $table) {
            $table->renameColumn('grouping','quantity');
        });
        
        Schema::table('splits', function (Blueprint $table) {
            $table->decimal('quantity', 8, 2)->nullable(true)->change();
            $table->dropColumn('split');
        });
    }
};
