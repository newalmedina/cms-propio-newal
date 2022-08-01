<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRoleRequest;
use App\Models\PermissionsTree;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminRoleController extends Controller
{


    public function index()
    {
        $pageTitle = trans('roles/admin_lang.roles');
        $title = trans('roles/admin_lang.list');
        $roles = Role::orderBy('id', 'asc')->get();

        return view('roles.admin_index', compact('pageTitle', 'title', "roles"));
    }

    public function edit($id)
    {
        $pageTitle = trans('roles/admin_lang.roles');
        $title = trans('roles/admin_lang.list');
        $role = Role::find($id);
        $tab = 'tab_1';
        return view('roles.admin_edit', compact('pageTitle', 'title', "role"))
            ->with('tab', $tab);
    }
    public function update(AdminRoleRequest $request, $id)
    {
        $role = Role::find($id);
        $role->display_name = $request->display_name;
        $role->save();
        return redirect()->route('admin.roles.edit', [$role->id])
            ->with('success', trans('general/admin_lang.save_ok'));
    }

    public function editPermissions($id)
    {
        $pageTitle = trans('roles/admin_lang.roles');
        $title = trans('roles/admin_lang.list');

        $permissionsTree = PermissionsTree::withDepth()->with('permission')->get()->sortBy('_lft');

        $role = Role::find($id);
        $a_arrayPermisos = $role->getArrayPermissions();

        if (is_null($role)) {
            app()->abort(500);
        }
        $tab = "tab_2";
        return view('roles.admin_edit_permissions', compact(
            'pageTitle',
            'title',
            'a_arrayPermisos',
            'permissionsTree',
            "role"
        ))
            ->with('tab', $tab);
    }

    public function updatePermissions(Request $request, $id)
    {
        $idpermissions = explode(",", $request->input('results'));


        // Compruebo que el rol al que se quieren asignar datos existe
        $role = Role::find($id);

        if (is_null($role)) {
            app()->abort(500);
        }
        try {
            DB::beginTransaction();

            // Asigno el array de permisos al rol
            $role->syncPermissions($idpermissions);

            DB::commit();

            // Y Devolvemos una redirección a la acción show para mostrar el usuario
            return redirect()->to('/admin/roles/permissions/' . $role->id)
                ->with('success', trans('general/admin_lang.save_ok'));
        } catch (\PDOException $e) {
            DB::rollBack();
            dd($e);
            return redirect()->to('/admin/roles/permissions/' . $role->id)
                ->with('error', trans('general/admin_lang.save_ko'));
        }
    }
}
