<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use Illuminate\Http\Request;

class AdminMunicipioController extends Controller
{
    public function getMunicipioListByProvince($id = null)
    {
        return Municipio::where("province_id", $id)->get();
        // return Municipio::active()->where("province_id", $id)->get();
    }
}
