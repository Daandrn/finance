<?php 

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait TransactionStatments
{
    public function begin()
    {
        DB::beginTransaction();
    }

    public function commit()
    {
        DB::commit();
    }

    public function rollback()
    {
        DB::rollBack();
    }
}