<?php

namespace App\Services;

use App\Traits\CosineSimilarityTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CosineSimilarityApiService
{
    use CosineSimilarityTraits;

    public $allTokenizing   = [];
    public $allFiltering    = [];
    public $allStemming     = [];

    public function execute($payload){
        try{

            $sentence = $payload['payload']['query'];
    
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
    
            // Preprocesssion Documents
            $preprocessingDocuments = $this->preProcessingDocumentApi($payload['payload']['documents']);
            // document x query 
            $documentResult['QUERY'] = $queryStemmingResult;
            foreach($preprocessingDocuments['stemming'] as $index => $value) {
                $documentResult[$index] = $value;
            }    

            // convert array in array to a singgle array
            $merged = call_user_func_array('array_merge', $documentResult);
            // make unique if there value same
            $unique = array_unique($merged);
            $data = [];
    
            //count document
            $countDocument = count($documentResult)-1;
            foreach($unique as $u){
                $tempDf = 0;
                foreach($documentResult as $key => $value){
                    //count tf
                    $tf[$key] = substr_count(implode(' ', $value),$u);
                    //count df
                    if(substr_count(implode(' ', $value),$u) > 0 && $key != 'QUERY'){
                        $tempDf++;
                    }
                }
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
            foreach($data as $d){
                array_push($tempCV, $d['CV']);
            }

            // for($i = 1 ; $i <= $countDocument; $i++){
            //     $sumCrossVector['D'.$i] = 0;
            // }

            foreach($payload['payload']['documents'] as $document){
                $sumCrossVector[$document['code']] = 0;
            }

            foreach ($tempCV as $k=>$subArray) {
                foreach ($subArray as $id=>$value) {
                    $sumCrossVector[$id]+=$value;
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
            // for($i = 1 ; $i <= $countDocument; $i++){
            //     $sumCS['D'.$i] = [];
            // }
            foreach($payload['payload']['documents'] as $document){
                $sumCS[$document['code']] = [];
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
            $documents = $payload['payload']['documents'];
            $documentAndQuery = array_merge($documentAndQuery, $documents);
    
            //get rangking of document
            $rangking = $this->getRangking($cosim);
            $result = [
                'percentage'        => $cosim,
                'rangking'          => $rangking,
            ];
            return $result;
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

    public function preProcessingDocumentApi($documents){

        //initiate array
        $tokenizingResultArray = [];
        $filteringResultArray = [];
        $stemmingResultArray = [];

        //initiate number
        $tokenizingNumber = 1;
        $filteringNumber = 1;
        $stemmingNumber = 1;

        foreach($documents as $document){
            $tokenizingResult = $this->tokenizing($document['content']);
            $tokenizingResultArray[$document['code']] = $tokenizingResult;

            /**
             * step 2 filtering
             * string input (implode tokenizing result)
             * an array output
             */
            $filteringResult = $this->filtering(implode(' ', $tokenizingResult));
            $filteringResultArray[$document['code']] = $filteringResult;
            /**
             * step 3 stemming
             * string input (implode filtering result)
             * an array output
             */
            $stemmingResult = $this->stemming(implode(' ', $filteringResult));
            $stemmingResultArray[$document['code']] = $stemmingResult;
        }
        
        $result = [
            'tokenizing' => $tokenizingResultArray,
            'filtering' => $filteringResultArray,
            'stemming' => $stemmingResultArray,
        ];

        return $result;
    }
}
