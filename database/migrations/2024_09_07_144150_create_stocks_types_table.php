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
        Schema::create('stocks_types', function (Blueprint $table) {
            $table->id();
            $table->boolean('has_irpf')->nullable(false);
            $table->string('description', 10)->nullable(false);
            $table->string('name', 50)->nullable(false);
        });

        $stocksTypes = [
            ['id' => 1, 'has_irpf' => true,  'description' => "AÇÕES", 'name' => "Ações"                                          ],
            ['id' => 2, 'has_irpf' => false, 'description' => "FII",   'name' => "Fundos de investimento imobiliário"             ],
            ['id' => 3, 'has_irpf' => true,  'description' => "BDR",   'name' => "Certificados de depósito de valores mobiliários"],
        ];
        
        DB::table('stocks_types')->insert($stocksTypes);

        $lastStocksTypes = DB::table('stocks_types')->aggregate('max', ['id']);
        if ($lastStocksTypes) {
            DB::statement("SELECT setval('stocks_types_id_seq', $lastStocksTypes, true)");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks_types');
    }
};
