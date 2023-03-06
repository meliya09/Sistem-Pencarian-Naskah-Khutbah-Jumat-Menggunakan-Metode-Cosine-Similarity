<?php

namespace App\Imports;

use App\Models\Document;
use Maatwebsite\Excel\Concerns\ToModel;

class DocumentImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return Document::firstOrCreate([
            'title' => $row['1'],
        ],[
            'content' => $row['2']
        ]);
    }
}
