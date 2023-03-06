<?php

namespace App\Http\Controllers;

use App\Imports\DocumentImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportDocumentController extends Controller
{
    public function __invoke(){
        try{
            Excel::import(new DocumentImport, 'documentkhutbah.xlsx');
        }catch(\Throwable $e){
            dd($e->getMessage(), $e->getLine());
        }
        
    }
}
