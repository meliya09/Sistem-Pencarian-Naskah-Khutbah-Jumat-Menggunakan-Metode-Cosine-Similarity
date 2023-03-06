<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use App\Traits\CosineSimilarityTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CosineSimilarityApiController extends Controller
{
    use ApiResponser;
    public function execute(Request $request){
        info('incoming request with params ' . json_encode($request->all()));
        try{
            $s = new \App\Services\CosineSimilarityApiService();
            $result  = $s->execute($request->all());
            return $this->success($result);
        }catch(\Throwable $th){
            Log::error([
                get_class($this) . 'execute with params ' . json_encode($request->all()),
                'Message ' . $th->getMessage(),
                'On file ' . $th->getFile(),
                'On line ' . $th->getLine()
            ]);
            return $this->error($th->getMessage(),400);
        }

    }
}
