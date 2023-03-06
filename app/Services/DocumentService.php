<?php

namespace App\Services;

use App\Traits\CosineSimilarityTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DocumentService
{
    public function store($data){
        try{
            $payload = [
                'content'   => $data['content'],
                'title'     => $data['title']
            ];
            $r = new \App\Repositories\DocumentRepository();
            return $r->store($payload);
        }catch(\Throwable $th){
            Log::error([
                get_class($this) . 'store' . json_encode($data),
                'Message ' . $th->getMessage(),
                'On line ' . $th->getLine(),
            ]);
            throw new \Exception($th->getMessage());
        }
    }

    public function update($data, $document){
        try{
            $payload = [
                'content'   => $data['content'],
                'title'     => $data['title']
            ];
            $r = new \App\Repositories\DocumentRepository();
            return $r->update($payload, $document);
        }catch(\Throwable $th){
            Log::error([
                get_class($this) . 'store' . json_encode($data),
                'Message ' . $th->getMessage(),
                'On line ' . $th->getLine(),
            ]);
            throw new \Exception($th->getMessage());
        }
    }
}
