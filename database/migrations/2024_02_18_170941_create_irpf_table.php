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
        Schema::create('irpf', function (Blueprint $table) {
            $table->id();
            $table->string('period', 30)->notnullable();
            $table->string('tax', 5)->notnullable();
            $table->timestamps();
        });

        $irpf = [
            ['id' => 1, 'period' => "Até 180 dias",         'tax' => "22.5"],
            ['id' => 2, 'period' => "Entre 181 e 360 dias", 'tax' => "20"  ],
            ['id' => 3, 'period' => "Entre 361 e 720 dias", 'tax' => "17.5"],
            ['id' => 4, 'period' => "Acima 721 dias",       'tax' => "15"  ],
        ];

        DB::table('irpf')->insert($irpf);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('irpf');
    }
};
