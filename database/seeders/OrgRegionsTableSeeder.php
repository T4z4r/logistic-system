<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrgRegion;

class OrgRegionsTableSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            ['01','Dodoma'], ['02','Arusha'], ['03','Kilimanjaro'], ['04','Tanga'],
            ['05','Morogoro'], ['06','Pwani'], ['07','Dar-es-salaam'], ['08','Lindi'],
            ['09','Mtwara'], ['10','Ruvuma'], ['11','Iringa'], ['12','Mbeya'],
            ['13','Singida'], ['14','Tabora'], ['15','Rukwa'], ['16','Kogoma'],
            ['17','Shinyanga'], ['18','Kagera'], ['19','Mwanza'], ['20','Mara'],
            ['21','Manyara'], ['22','Njombe'], ['23','Katavi'], ['24','Simiyu'],
            ['25','Geita'], ['51','Kaskazini Unguja'], ['52','Kusini Unguja'],
            ['53','Mjini Magharibi'], ['54','Kaskazini Pemba'], ['55','Kusini Pemba'],
        ];

        foreach ($regions as $region) {
            OrgRegion::create([
                'reg_code' => $region[0],
                'reg_name' => $region[1],
            ]);
        }
    }
}
