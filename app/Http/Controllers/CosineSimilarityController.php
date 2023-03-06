<?php

namespace App\Http\Controllers;

use App\Traits\CosineSimilarityTraits;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Sastrawi\Stemmer\StemmerFactory;
class CosineSimilarityController extends Controller
{
    use CosineSimilarityTraits;

    public $allTokenizing   = [];
    public $allFiltering    = [];
    public $allStemming     = [];

    public function check(Request $request){
        $validated = $this->validate($request, [
            'query' => 'required',
        ]);
        set_time_limit(120);
        try{
            $stemmerFactory = new StemmerFactory();
            $stemmer  = $stemmerFactory->createStemmer();
            $sentence = $validated['query'];
            $output   = $stemmer->stem($sentence);
    
            //step 1 tokenizing | an array result with string input
            $tokenizingResult = $this->tokenizing($sentence);
            $this->allTokenizing['QUERY'] = $tokenizingResult;
    
            /**
             * step 2 filtering
             * string input (implode tokenizing result)
             * an array output
             */
            $filteringResult = $this->filtering(implode(' ', $tokenizingResult));
            $this->allFiltering['QUERY'] = $filteringResult;
    
            /**
             * step 3 stemming
             * string input (implode filtering result)
             * an array output
             */
            $queryStemmingResult = $this->stemming(implode(' ', $filteringResult));
            $this->allStemming['QUERY'] = $queryStemmingResult;
    
            // preprocesssion Documents
            $preprocessingDocuments = $this->prePocessingDocuments();
            
            // document x query 
            $documentResult['QUERY'] = $queryStemmingResult;
            $documentResult = array_merge($documentResult,$preprocessingDocuments['stemming']);
    
            $this->allTokenizing = array_merge($this->allTokenizing,$preprocessingDocuments['tokenizing']);
            $this->allFiltering  = array_merge($this->allFiltering,$preprocessingDocuments['filtering']);
            $this->allStemming = array_merge($this->allStemming,$preprocessingDocuments['stemming']);
    
            // convert array in array to a singgle array
            $merged = call_user_func_array('array_merge', $documentResult);
            // make unique if there value same
            $unique = array_unique($merged);
            $data = [];
    
            //count document
            $countDocument = count($documentResult)-1;
            foreach($unique as $u){
                $tempDf = 0;
                // dd($documentResult);
                foreach($documentResult as $key => $value){

                    $arrayCountValues = array_count_values($value);
                    $tf[$key] = $arrayCountValues[$u] ?? 0;
                    //count df
                    if($arrayCountValues[$u] ?? 0 > 0){
                        if($key != 'QUERY'){
                            $tempDf++;
                        }
                    }
                }
                // dd($tempDf);
                $data[$u]['tf'] = $tf;
                $data[$u]['df'] = $tempDf;
                $data[$u]['D/df'] = $tempDf == 0 ?  0 : $countDocument/$tempDf;
                $data[$u]['Idf'] = $tempDf == 0 ? 0 : log10($countDocument/$tempDf);
                $data[$u]['Idf+1'] = $tempDf == 0 ? 0 +1 : log10($countDocument/$tempDf ?? 0)+1;
                // PERHITUNGAN TF/IDF = TF * IDF => W
                foreach($tf as $t => $k){
                    $w[$t] = $data[$u]['Idf+1']*$k;
                }
                $data[$u]['W'] = $w;
    
                // HASIL PERKALIAN SKALAR TIAP D TERHADAP Q = JI . Q => CV
                foreach($w as $ww => $k){
                    if($ww != 'QUERY') $crosVector[$ww] = $w['QUERY']*$w[$ww];
                }
                $data[$u]['CV'] = $crosVector;
    
                // HASIL PERKALIAN VEKTOR Q DAN D (TF/IDF(Q,D))2
                foreach($w as $ww => $k){                
                    $crossScale[$ww] = pow($k, 2); 
                }
                $data[$u]['CS'] = $crossScale;
            }
    
            /**
             * SUM CROSS VECTOR
             * 1. STORE ALL DATA CV INTO AN ARRAY => $tempCV
             * 2. CREATE AN ARRAY FOR SAVE THE VALUE => $sumCrossVector
             * 3. START THE CODE
             */
            $tempCV = [];
            foreach($data as $index => $d){
                $d['CV']['query'] = $index;
                array_push($tempCV, $d['CV']);
            }
            for($i = 1 ; $i <= $countDocument; $i++){
                $sumCrossVector['D'.$i] = 0;
            }
            foreach ($tempCV as $k=>$subArray) {
                foreach ($subArray as $id=>$value) {
                    if(in_array($subArray['query'], $this->allStemming['QUERY'])){
                        if($id != 'query'){
                            $sumCrossVector[$id]+=$value;
                        }
                    }
                }
            }        
            /** END */
            
            /**
             * SUM CS
             * 1. STORE ALL DATA CS INTO AN ARRAY => $tempCS
             * 2. CREATE AN ARRAY FOR SAVE THE VALUE => $sumCS
             * 3. START THE CODE
             */
            $tempCS = [];
            foreach($data as $d){
                array_push($tempCS, $d['CS']);
            }
            $sumCS['QUERY'] = [];
            for($i = 1 ; $i <= $countDocument; $i++){
                $sumCS['D'.$i] = [];
            }
            foreach ($tempCS as $k=>$subArray) {
                foreach ($subArray as $id=>$value) {
                    array_push($sumCS[$id], $value);
                }
            }
            $sqrtCs = [];
            foreach($sumCS as $k => $value){
                $a = array_filter($value);
                $sum = array_sum($a);
                $sqrtCs[$k] = sqrt($sum);
            }
            $cosim = [];
            foreach($sumCrossVector as $scv => $k){
                $cosim[$scv] = $k / ($sqrtCs['QUERY'] * $sqrtCs[$scv]);
            }
    
            // get all documents and merge with query
            $documentAndQuery = [
                $sentence
            ];
            $documents = Document::latest()->pluck('content')->toArray();
            $documentAndQuery = array_merge($documentAndQuery, $documents);
            //storing document and query to session
            // session(['documentAndQuery' => $documentAndQuery]);
    
            //get rangking of document
            $rangking = $this->getRangking($cosim);
            //sorting rangking
            asort($rangking);
            $mergeRanking = $this->mergeRangking($rangking);

            $result = [
                'terms'             => $data,
                'sumCrossVector'    => $sumCrossVector,
                'sqrtCs'            => $sqrtCs,
                'documentAndQuery'  => $documentAndQuery,
                'allTokenizing'     => $this->allTokenizing,
                'allFiltering'      => $this->allFiltering,
                'allStemming'       => $this->allStemming,
                'rangkings'         => $mergeRanking,
            ];
            // dd($result);

            $this->syncTempDocument($result);
            
            return view('cosim.index', $result);
        }catch(\Throwable $th){
            dd($th->getMessage(), $th->getLine(), $th->getFile());
            Log::error([
                get_class($this) . 'check',
                'Message ' . $th->getMessage(),
                'On line ' . $th->getLine(),
            ]);
            return redirect(route('cosinesimilarity'))->with('alert', $th->getMessage());
        }
    }

    public function mergeRangking($rangking){
        $arrayRangkings = [];
        $documents = \App\Models\Document::latest()->get();

        foreach($rangking as $key => $value){
            $documentNumber = 0;
            foreach($documents as $document){
                $documentNumber++;
                if('D'.$documentNumber == $key){
                    $arrayRangkingData = [
                        'rangking' => $value,
                        'document_code' => $key, // code is D1, D2,D2, ... , DN
                        'document_id' => $document->id,
                        'document_title' => $document->title,
                        'document_number'   => $key
                    ];
                    array_push ($arrayRangkings, $arrayRangkingData);
                    break;
                }
            }
        }

        return $arrayRangkings;
    }

}
