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
        Schema::create('tittle_types', function (Blueprint $table) {
            $table->id();
            $table->string("description")->notnullable();
            $table->boolean("has_irpf")->notnullable();
            $table->timestamps();
        });

        $types = [
            ["id" => 1, "description" => "Cdb",           "has_irpf" => true],
            ["id" => 2, "description" => "Lci",           "has_irpf" => false],
            ["id" => 3, "description" => "Lca",           "has_irpf" => false],
            ["id" => 4, "description" => "Cri",           "has_irpf" => false],
            ["id" => 5, "description" => "Cra",           "has_irpf" => false],
            ["id" => 6, "description" => "Tesouro Selic", "has_irpf" => true],
            ["id" => 7, "description" => "Ações",         "has_irpf" => true],
            ["id" => 8, "description" => "Fiis",          "has_irpf" => false],
            ["id" => 9, "description" => "Rdb",           "has_irpf" => true],
            ["id" => 10, "description" => "Tesouro IPCA", "has_irpf" => true]
        ];

        DB::table('tittle_types')->insert($types);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tittle_types');
    }
};
