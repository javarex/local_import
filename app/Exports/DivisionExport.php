<?php

namespace App\Exports;

use App\Division;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DivisionExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $divisions = Division::select('id','office_id','division')->get();
        return $divisions;
    }

    public function headings(): array
    {
        return [
            'ID',
            'OFFICE_ID',
            'DIVISION',
        ];

        
    }

    public function map($division): array
    {
        
        return [
            $division->id,
            $division->office_id,
            $division->division,
            
          
        ];
    }
}
