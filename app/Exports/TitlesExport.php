<?php

namespace App\Exports;

use App\Models\Title;
use Maatwebsite\Excel\Concerns\FromCollection;

class TitlesExport implements FromCollection
{
    public function collection()
    {
        return Title::all();
    }
}