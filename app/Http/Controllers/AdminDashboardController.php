<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $pageTitle = trans('dashboard/admin_lang.dashboard');
        $title = trans('dashboard/admin_lang.dashboard');

        return view('dashboard.admin_index', compact('pageTitle', 'title'));
    }
}
