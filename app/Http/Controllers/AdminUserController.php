<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminUserRequest;
use App\Models\Center;
use App\Models\PermissionsTree;
use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class AdminUserController extends Controller
{


    public function index()
    {
        if (!auth()->user()->isAbleTo('admin-users')) {
            app()->abort(403);
        }


        $pageTitle = trans('users/admin_lang.users');
        $title = trans('users/admin_lang.list');
        $users = User::orderBy('id', 'asc')->get();

        return view('users.admin_index', compact('pageTitle', 'title', "users"));
    }

    public function create()
    {
        if (!auth()->user()->isAbleTo('admin-users-create')) {
            app()->abort(403);
        }
        $pageTitle = trans('users/admin_lang.new');
        $title = trans('users/admin_lang.list');
        $user = new User();;
        $tab = 'tab_1';

        return view('users.admin_edit', compact('pageTitle', 'title', "user"))
            ->with('tab', $tab);
    }

    public function edit($id)
    {
        if (!auth()->user()->isAbleTo('admin-users-update')) {
            app()->abort(403);
        }
        $user = User::with('userProfile')->find($id);

        if (empty($user)) {
            app()->abort(404);
        }

        $pageTitle = trans('users/admin_lang.edit');
        $title = trans('users/admin_lang.list');
        $tab = 'tab_1';

        return view('users.admin_edit', compact('pageTitle', 'title', "user"))
            ->with('tab', $tab);
    }

    public function update(AdminUserRequest $request, $id)
    {
        if (!auth()->user()->isAbleTo('admin-users-update')) {
            app()->abort(403);
        }

        try {
            DB::beginTransaction();

            $user = User::with('userProfile')->find($id);

            $user->email = $request->input('email');
            $user->active = $request->input('active', 0);

            if (!empty($request->input('password'))) {
                $user->password = Hash::make($request->input('password'));
            }
            $user->userProfile->first_name = $request->input('user_profile.first_name');
            $user->userProfile->last_name = $request->input('user_profile.last_name');
            $user->push();

            DB::commit();
            toastr()->success(trans('general/admin_lang.save_ok'));

            return redirect()->route('admin.users.edit', [$user->id]); // ->with('success-alert', trans('general/admin_lang.save_ok'));
        } catch (\Exception $e) {
            DB::rollBack();
            toastr()->error(trans('general/admin_lang.save_ko'));
            return redirect('admin/users/create/' . $user->id); // ->with('error-alert', trans('general/admin_lang.save_ko') . ' - ' . $e->getMessage());
        }
    }

    public function store(AdminUserRequest $request)
    {
        if (!auth()->user()->isAbleTo('admin-users-create')) {
            app()->abort(403);
        }

        try {
            DB::beginTransaction();

            $user = new User();

            $user->email = $request->input('email');
            $user->active = $request->input('active', 0);
            $user->email_verified_at = Carbon::now();

            if (!empty($request->input('password'))) {
                $user->password = Hash::make($request->input('password'));
            }
            $user->save();

            if (!empty($user->id)) {

                $userProfile = new UserProfile();

                $userProfile->user_id = $user->id;
                $userProfile->first_name = $request->input('user_profile.first_name');
                $userProfile->last_name = $request->input('user_profile.last_name');
                $userProfile->save();
            }

            DB::commit();
            toastr()->success(trans('general/admin_lang.save_ok'));

            return redirect()->route('admin.users.edit', [$user->id]); // ->with('success-alert', trans('general/admin_lang.save_ok'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            toastr()->error(trans('general/admin_lang.save_ko'));
            return redirect('admin/users/create'); // ->with('error-alert', trans('general/admin_lang.save_ko') . ' - ' . $e->getMessage());
        }
    }

    public function getData()
    {
        if (!auth()->user()->isAbleTo('admin-users-list')) {
            app()->abort(403);
        }
        $query = User::select([
            'users.id',
            'users.email',
            'users.active',
            'user_profiles.first_name',
            'user_profiles.last_name',
        ])
            ->leftJoin("user_profiles", "user_profiles.user_id", "=", "users.id");

        $table = DataTables::of($query);

        $table->editColumn('active', function ($data) {
            $permision = "";
            if (!auth()->user()->isAbleTo('admin-users-update')) {
                $permision = "disabled";
            }

            $state = $data->active ? "checked" : "";

            return  '<div class="form-check form-switch text-center">
                <input class="form-check-input" onclick="changeState(' . $data->id . ')" ' . $state . '  ' . $permision . '  value="1" name="active" type="checkbox" id="active">
            </div>';
        });
        $table->editColumn('centers', function ($data) {

            $centers = DB::table("user_centers")->join("centers", "centers.id", "=", "user_centers.center_id")->where("user_centers.user_id", 1)->pluck("centers.name")->toArray();

            return implode(", ", $centers);
        });

        $table->editColumn('actions', function ($data) {
            $actions = '';
            if (auth()->user()->isAbleTo("admin-users-update")) {
                $actions .= '<a  class="btn btn-info btn-sm" href="' . route('admin.users.edit', $data->id) . '" ><i
                class="fa fa-marker fa-lg"></i></a> ';
            }

            if (auth()->user()->isAbleTo("admin-users-delete")) {

                $actions .= '<button class="btn btn-danger btn-sm" onclick="javascript:deleteElement(\'' .
                    url('admin/users/' . $data->id) . '\');" data-content="' .
                    trans('general/admin_lang.borrar') . '" data-placement="left" data-toggle="popover">
                        <i class="fa fa-trash" aria-hidden="true"></i></button>';
            }
            if (auth()->user()->isAbleTo("admin-users-suplant-identity") && auth()->user()->id != $data->id) {

                $actions .= '<a  class="btn btn-primary btn-sm ms-1" href="' . route('admin.suplantar', $data->id) . '" ><i
                class="fa fa-user-secret fa-lg"></i></a> ';
            }

            return $actions;
        });

        $table->removeColumn('id');
        $table->rawColumns(['actions', 'active', 'centers']);
        return $table->make();
    }

    public function destroy($id)
    {
        // Si no tiene permisos para modificar lo echamos
        if (!auth()->user()->isAbleTo('admin-users-delete')) {
            app()->abort(403);
        }
        $user = User::find($id);
        if (empty($user)) {
            app()->abort(404);
        }

        $user->delete();

        return response()->json(array(
            'success' => true,
            'msg' => trans("general/admin_lang.delete_ok"),
        ));
    }

    public function changeState($id)
    {
        if (!auth()->user()->isAbleTo('admin-users-update')) {
            app()->abort(403);
        }

        $user = User::find($id);

        if (!empty($user)) {
            $user->active = !$user->active;
            return $user->save() ? 1 : 0;
        }

        return 0;
    }

    public function editRoles($id)
    {
        if (!auth()->user()->isAbleTo('admin-users-update')) {
            app()->abort(403);
        }
        $user = User::find($id);
        if (is_null($user)) {
            app()->abort(500);
        }
        $pageTitle = trans('users/admin_lang.users');
        $title = trans('users/admin_lang.list');


        $roles = Role::active()->get();
        $tab = "tab_2";
        return view('users.admin_edit_roles', compact(
            'pageTitle',
            'title',
            "user",
            'roles'
        ))
            ->with('tab', $tab);
    }

    public function updateRoles(Request $request, $id)
    {

        if (!auth()->user()->isAbleTo('admin-users-update')) {
            app()->abort(403);
        }

        $user = User::find($id);

        if (is_null($user)) {
            app()->abort(500);
        }
        $idroles = explode(",", $request->input('role_ids'));
        try {
            DB::beginTransaction();
            $user->syncRoles($idroles);
            DB::commit();
            toastr()->success(trans('general/admin_lang.save_ok'));

            // Y Devolvemos una redirección a la acción show para mostrar el usuario
            return redirect()->to('/admin/users/roles/' . $user->id); // ->with('success-alert', trans('general/admin_lang.save_ok'));
        } catch (\PDOException $e) {
            DB::rollBack();
            dd($e);
            toastr()->error(trans('general/admin_lang.save_ko'));
            return redirect()->to('/admin/users/roles/' . $user->id);
            // ->with('error-alert', trans('general/admin_lang.save_ko'));
        }
    }

    public function editCenters($id)
    {
        if (!auth()->user()->isAbleTo('admin-users-update')) {
            app()->abort(403);
        }
        $user = User::find($id);
        if (is_null($user)) {
            app()->abort(500);
        }
        $pageTitle = trans('users/admin_lang.users');
        $title = trans('users/admin_lang.list');
        $selected_center = [];
        foreach ($user->centers as $center) {
            $selected_center[] = $center->id;
        }
        $centers = Center::active()->get();

        $tab = "tab_3";

        return view('users.admin_centers', compact(
            'pageTitle',
            'title',
            "user",
            'centers',
            'selected_center'
        ))
            ->with('tab', $tab);
    }

    public function updateCenters(Request $request, $id)
    {

        if (!auth()->user()->isAbleTo('admin-users-update')) {
            app()->abort(403);
        }


        $user = User::find($id);

        if (is_null($user)) {
            app()->abort(500);
        }

        try {
            DB::beginTransaction();
            $user->centers()->sync($request->center_id);


            DB::commit();
            toastr()->success(trans('general/admin_lang.save_ok'));

            // Y Devolvemos una redirección a la acción show para mostrar el usuario
            return redirect()->to('/admin/users/centers/' . $user->id); // ->with('success-alert', trans('general/admin_lang.save_ok'));
        } catch (\PDOException $e) {
            DB::rollBack();
            dd($e);
            toastr()->error(trans('general/admin_lang.save_ko'));
            return redirect()->to('/admin/users/centers/' . $user->id);
            // ->with('error-alert', trans('general/admin_lang.save_ko'));
        }
    }
}
