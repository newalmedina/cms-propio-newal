<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminCategoryController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAbleTo('admin-categories')) {
            app()->abort(403);
        }
        $pageTitle = trans('categories/admin_lang.categories');
        $title = trans('categories/admin_lang.list');
        return view('categories.admin_index', compact('pageTitle', 'title'));
    }
}
