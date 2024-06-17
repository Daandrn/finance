<?php declare(strict_types=1);

namespace App\Imports\excel;

use Maatwebsite\Excel\Excel;

class ImportsExcel
{
    public function importExcel()
    {
        Excel::import();
    }
}
