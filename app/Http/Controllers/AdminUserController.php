<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdmiUserRequest;
use App\Models\PermissionsTree;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminUserController extends Controller
{


    public function index()
    {
        $pageTitle = trans('users/admin_lang.users');
        $title = trans('users/admin_lang.list');
        $users = User::orderBy('id', 'asc')->get();

        return view('users.admin_index', compact('pageTitle', 'title', "users"));
    }

    public function edit($id)
    {
        $pageTitle = trans('users/admin_lang.users');
        $title = trans('users/admin_lang.list');
        $user = User::with('userProfile')->find($id);
        $tab = 'tab_1';

        return view('users.admin_edit', compact('pageTitle', 'title', "user"))
            ->with('tab', $tab);
    }
    public function update(AdmiUserRequest $request, $id)
    {
        $user = User::find($id);
        $user->display_name = $request->display_name;
        $user->save();
        return redirect()->route('admin.users.edit', [$user->id])
            ->with('success', trans('general/admin_lang.save_ok'));
    }

    public function editPermissions($id)
    {
        $pageTitle = trans('users/admin_lang.users');
        $title = trans('users/admin_lang.list');

        $permissionsTree = PermissionsTree::withDepth()->with('permission')->get()->sortBy('_lft');

        $user = User::find($id);
        $a_arrayPermisos = $user->getArrayPermissions();

        if (is_null($user)) {
            app()->abort(500);
        }
        $tab = "tab_2";
        return view('users.admin_edit_permissions', compact(
            'pageTitle',
            'title',
            'a_arrayPermisos',
            'permissionsTree',
            "User"
        ))
            ->with('tab', $tab);
    }

    public function updatePermissions(Request $request, $id)
    {
        $idpermissions = explode(",", $request->input('results'));


        // Compruebo que el rol al que se quieren asignar datos existe
        $user = User::find($id);

        if (is_null($user)) {
            app()->abort(500);
        }
        try {
            DB::beginTransaction();

            // Asigno el array de permisos al rol
            $user->syncPermissions($idpermissions);

            DB::commit();

            // Y Devolvemos una redirección a la acción show para mostrar el usuario
            return redirect()->to('/admin/users/permissions/' . $user->id)
                ->with('success', trans('general/admin_lang.save_ok'));
        } catch (\PDOException $e) {
            DB::rollBack();
            dd($e);
            return redirect()->to('/admin/users/permissions/' . $user->id)
                ->with('error', trans('general/admin_lang.save_ko'));
        }
    }
}
