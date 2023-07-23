<?php

namespace Database\Seeders;

use App\Models\Center;
use App\Models\Municipio;
use App\Models\Province;
use Illuminate\Database\Seeder;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InsertCenterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $center = new  Center();
        $center->name = "Mi Centro";
        $center->active = 1;
        $center->default = 1;
        $center->save();
    }
}
