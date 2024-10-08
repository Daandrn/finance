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
        Schema::create('title_types', function (Blueprint $table) {
            $table->id();
            $table->boolean('has_irpf')->nullable(false);
            $table->string('description', 30)->nullable(false);
            $table->string('name', 255)->nullable(false);
            $table->timestamps();
        });

        $types = [
            ['id' => 1, 'has_irpf' => true,  'description' => "CDB",          'name' => "Certificado de depósito bancário"              ],
            ['id' => 2, 'has_irpf' => false, 'description' => "LCI",          'name' => "Letra de crédito do setor imobiliário"         ],
            ['id' => 3, 'has_irpf' => false, 'description' => "LCA",          'name' => "Letra de crédito do agronegócio"               ],
            ['id' => 4, 'has_irpf' => false, 'description' => "CRI",          'name' => "Certificado de recebíveis do setor imobiliário"],
            ['id' => 5, 'has_irpf' => false, 'description' => "CRA",          'name' => "Certificado de recebíveis do agronegócio"      ],
            ['id' => 6, 'has_irpf' => true,  'description' => "SELIC",        'name' => "Tesouro selic"                                 ],
            ['id' => 7, 'has_irpf' => true,  'description' => "TESOURO IPCA", 'name' => "Tesouro IPCA"                                  ],
            ['id' => 8, 'has_irpf' => true,  'description' => "DEB",          'name' => "Debenture"                                     ],
            ['id' => 9, 'has_irpf' => true,  'description' => "RDB",          'name' => "Recibo de depósito bancário"                   ],
        ];

        DB::table('title_types')->insert($types);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('title_types');
    }
};
