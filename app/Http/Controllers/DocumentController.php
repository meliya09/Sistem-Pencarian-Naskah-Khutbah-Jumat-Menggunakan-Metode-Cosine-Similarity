<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Repositories\DocumentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $r = new \App\Repositories\DocumentRepository();
        $data = [
            'documents' => $r->all(),
        ];
        return view('documents.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $validate = $this->validate($request, [
                'content'   => 'required',
                'title'     => 'required|string|max:250'
            ]);
            $s = new \App\Services\DocumentService();
            $s->store($validate);
            return redirect(route('documents.index'))->with('alert-success', 'Data created successfully');
        }catch(\Throwable $th){
            Log::error([
                get_class($this) . 'store' . json_encode($request->all()),
                'Message ' . $th->getMessage(),
                'On line ' . $th->getLine(),
            ]);
            return back()->with('alert', $th->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document)
    {
        //

        try{
            $data = [
                'document' => $document
            ];

            return view('documents.show', $data);
        }catch(Throwable $th){
            Log::error([
                'Message ' . $th->getMessage(),
                'On line ' . $th->getLine(),
                'On file ' . $th->getFile()
            ]);
            return back()->with('alert', $th->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Document $document)
    {
        try{
            $data = [
                'document' => $document
            ];

            return view('documents.edit', $data);
        }catch(Throwable $th){
            Log::error([
                'Message ' . $th->getMessage(),
                'On line ' . $th->getLine(),
                'On file ' . $th->getFile()
            ]);
            return back()->with('alert', $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Document $document)
    {
        try{
            $validate = $this->validate($request, [
                'title'     => 'required|string|max:255',
                'content'   => 'required'
            ]);
    
            $s = new \App\Services\DocumentService();
            $s->update($validate, $document->id);
            return redirect(route('documents.index'))->with('alert-success', 'Document successfully added');
        }catch(\Throwable $th){
            Log::error([
                get_class($this) . 'update with params ' . json_encode($request->all()),
                'Message ' . $th->getMessage(),
                'On line ' . $th->getLine(),
            ]);
            return back()->with('alert', $th->getMessage())->withInput();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document, DocumentRepository $documentRepository)
    {
        try{
            $documentRepository->delete($document->id);
            return redirect(route('documents.index'))->with('alert-success', 'Document successfully destroyed');
        }catch(\Throwable $th){
            Log::error([
                get_class($this) . 'destroy',
                'Message ' . $th->getMessage(),
                'On line ' . $th->getLine(),
            ]);
            return back()->with('alert', $th->getMessage())->withInput();
        }
    }
}
