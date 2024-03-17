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
        Schema::create('modalities', function (Blueprint $table) {
            $table->id();
            $table->string('description', 30);
            $table->timestamps();
        });

        $modalities = [
            ['id' => 1, 'description' => "Pré-fixado"  ],
            ['id' => 2, 'description' => "CDI"         ],
            ['id' => 3, 'description' => "CDI+"        ],
            ['id' => 4, 'description' => "IPCA"        ],
            ['id' => 5, 'description' => "IPCA+"       ],
            ['id' => 6, 'description' => "Selic"       ],
            ['id' => 7, 'description' => "Selic+"      ],
        ];

        DB::table('modalities')->insert($modalities);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modalities');
    }
};
