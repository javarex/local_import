<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\DivisionExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportDivision()
    {
        return Excel::download(new DivisionExport, 'division_summary.xlsx');
    }
}
