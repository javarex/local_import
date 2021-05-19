<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class ImportController extends Controller
{
    public function importDivsion(Request $request)
    {
        $division_array = [];
        $dateTime = Carbon::now();
        $file = $request->division_file;
        $validate = $request->validate([
            'division_file' => 'required|mimes:xlsx,csv',
        ]);
        if ($validate) {
            $fileName = $file->getClientOriginalName();
            $file->move(storage_path('app/public'), "file.xlsx");
            $reader = ReaderEntityFactory::createReaderFromFile(storage_path('app/public') . "file.xlsx");

            $reader->open(public_path() . "/storage/file.xlsx");

            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $rowIndex => $row) {
                    $cells = $row->getCells();

                    $division_id = $cells[0]->getValue();
                    $office_id = $cells[1]->getValue();
                    $division = $cells[2]->getValue();
                    $acronym = $cells[3]->getValue();
                    // skip unwanted rows
                    if ($rowIndex === 1) {
                        continue;
                    } else {
                        DB::table('divisions')
                            ->where('id', $division_id)
                            ->update(['acronym' => $acronym]);
                        // $data_division = [
                        //     'id'            => $division_id,
                        //     'office_id'       => $office_id,
                        //     'division'       => $division,
                        //     'acronym'       => $acronym,
                        //     'created_at'    => $dateTime,
                        //     'updated_at'    => $dateTime
                            
                        // ];
                    }
                    // array_push($division_array,$data_division);
                }
            }
            $reader->close();
            // $chunk_division = array_chunk($division_array, 15);

            // foreach ($chunk_division as $key => $value) {
            //     DB::table('divisions')->insert($value);
            // }
            Storage::disk('public')->delete('file.xlsx');
        }

        return redirect('/')->with('import_response','Importing successful');
        
    }
}
