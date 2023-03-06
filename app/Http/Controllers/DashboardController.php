<?php

namespace App\Http\Controllers;

use App\Repositories\DocumentRepository;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(DocumentRepository $documentRepository)
    {
        $data = [
            'documents' => $documentRepository->all(),
        ];
        return view('home', $data);
    }
}
