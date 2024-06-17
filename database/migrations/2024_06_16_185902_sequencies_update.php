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
        $lastModalityId = DB::table('modalities')->aggregate('max', ['id']);
        if ($lastModalityId) {
            DB::statement("SELECT setval('modalities_id_seq', $lastModalityId, true)");
        }

        $lastUserId = DB::table('users')->aggregate('max', ['id']);
        if ($lastUserId) {
            DB::statement("SELECT setval('users_id_seq', $lastUserId, true)");
        }

        $lastTitleTypeId = DB::table('title_types')->aggregate('max', ['id']);
        if ($lastTitleTypeId) {
            DB::statement("SELECT setval('title_types_id_seq', $lastTitleTypeId, true)");
        }

        $lastIrpfId = DB::table('irpf')->aggregate('max', ['id']);
        if ($lastIrpfId) {
            DB::statement("SELECT setval('irpf_id_seq', $lastIrpfId, true)");
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
