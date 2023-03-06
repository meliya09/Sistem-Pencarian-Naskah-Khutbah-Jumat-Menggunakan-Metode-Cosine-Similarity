<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Seeder;

class DocumentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $documents = [
            [
                'content' => "Marilah kita senantiasa beribadah kepada-Nya adalah kewajiban yang harus kita lakukan hingga ajal mendatangi kita. 'Dan beribadahlah kepada Rabb-mu sampai kematian mendatangimu.'(AlHijr:99).",
            ],
            [
                'content' => "Ibadahlah! Salah satu prinsip besar yang dibangun oleh agama kita ialah prinsip ukhuwwah (persaudaraan) diantara sesama orang beriman. 'Sesungguhnya orangorang mukmin adalah bersaudara.' (Alhujurat : 10).",
            ],
            [
                'content' => "Ibadah adalah satu istilah yang menghimpun seluruh apa yang dicintai dan diridhai oleh Allah, baik berupa perkataan dan perbuatan, yang lahir dan yang batin.",
            ],

            // [
            //     'content' => "data data mentah sekumpulan informasi",
            // ],
            // [
            //     'content' => "belum bisa data diolah fakta informasi mentah menyajikan",
            // ],
            // [
            //     'content' => "acuan belum belum bisa data dijadikan diolah informasi keputusan mentah pengambilan",
            // ],

            // [
            //     'content' => "mari senantiasa ibadah wajib lakukan ajal datang ibadah rabb mati datang alHijr",
            // ],
            // [
            //     'content' => "ibadah salah prinsip bangun agama prinsip ukhuwwah saudara orang iman sungguh orang orang mukmin saudara alhujurat",
            // ],
            // [
            //     'content' => "ibadah istilah himpun cinta ridha allah lahir batin",
            // ],
        ];

        foreach($documents as $document){
            Document::firstOrCreate([
                'content' => $document['content']
            ],[
                'title' => $document['content']
            ]);
        }
    }
}
