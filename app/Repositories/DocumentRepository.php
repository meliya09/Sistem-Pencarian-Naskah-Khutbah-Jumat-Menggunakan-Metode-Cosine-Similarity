<?php

namespace App\Repositories;

use App\Models\Document;

class DocumentRepository
{

    public function all(){
        return Document::latest()->get();
    }

    public function store(array $data){
        return Document::create($data);
    }

    public function delete($id){
        return Document::find($id)->delete();
    }
    public function update(array $data , $id){
        return Document::find($id)->update($data);
    }
}
