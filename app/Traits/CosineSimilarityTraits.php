<?php

namespace App\Traits;

use App\Models\Document;
use App\Models\Temp;
use Sastrawi\Stemmer\StemmerFactory;

trait CosineSimilarityTraits{

    public function tokenizing(string $string){
        $string = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $string);
        return array_filter(explode(" ",$string));
    }

    public function filtering(string $string){
        $newString = trim(preg_replace('/\s\s+/', ' ', $string));
        $data = explode(' ', $newString);
        $stopwordArray = explode(',',config('cosine_similarity.stopword'));
        foreach($data as $key => $value) {
            if(in_array(strtolower($value), $stopwordArray)) {  
                unset($data[$key]);
            }
        }
        return $data;
    }

    public function stemming(string $string) {
        $stemmerFactory = new StemmerFactory();
        $stemmer  = $stemmerFactory->createStemmer();
        $output   = $stemmer->stem($string);
        return explode(' ',$output);
    }

    public function prePocessingDocuments(){
        // get all documents
        $documents = Document::latest()->get();

        //initiate array
        $tokenizingResultArray = [];
        $filteringResultArray = [];
        $stemmingResultArray = [];

        //initiate number
        $tokenizingNumber = 1;
        $filteringNumber = 1;
        $stemmingNumber = 1;

        foreach($documents as $document){
            $tokenizingResult = $this->tokenizing($document->title.' '.$document->content);
            $tokenizingResultArray['D'.$tokenizingNumber++] = $tokenizingResult;

            /**
             * step 2 filtering
             * string input (implode tokenizing result)
             * an array output
             */
            $filteringResult = $this->filtering(implode(' ', $tokenizingResult));
            $filteringResultArray['D'.$filteringNumber++] = $filteringResult;
            /**
             * step 3 stemming
             * string input (implode filtering result)
             * an array output
             */
            $stemmingResult = $this->stemming(implode(' ', $filteringResult));
            $stemmingResultArray['D'.$stemmingNumber++] = $stemmingResult;
        }
        
        $result = [
            'tokenizing' => $tokenizingResultArray,
            'filtering' => $filteringResultArray,
            'stemming' => $stemmingResultArray,
        ];

        return $result;
    }

    public function getRangking(array $data){
        $ordered_values = $data;
        rsort($ordered_values);
        $rangking = [];
        foreach ($data as $index => $value) {
            foreach ($ordered_values as $ordered_key => $ordered_value) {
                if ($value === $ordered_value) {
                    $key = $ordered_key;
                    break;
                }
            }
            $rangking[$index] = ((int) $key + 1);
        }
        return $rangking;
    }

    public function syncTempDocument($data){
        $temp = json_encode($data);
        Temp::latest()->delete();
        Temp::create([
            'payload' => $temp,
        ]);
        return true;
    }
}