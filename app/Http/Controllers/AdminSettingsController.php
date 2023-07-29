<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use Illuminate\Http\Request;
use Spatie\LaravelSettings\Facades\Settings;

class AdminSettingsController extends Controller
{
    public function index()
    {
        // if (!auth()->user()->isAbleTo('admin-settings')) {
        //     app()->abort(403);
        // }
        // $configuracion = Settings::all();

        // $pageTitle = trans('settings/admin_lang.settings');
        // $title = trans('settings/admin_lang.settings');

        // return view('settings.admin_index', compact('pageTitle', 'title'));
    }
}
