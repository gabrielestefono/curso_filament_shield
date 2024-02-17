<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class StudentsExport implements FromCollection
{
    public function __construct(public Collection $records)
    {
        $this->records = $records;
    }

    public function collection()
    {
        return $this->records;
    }
}
