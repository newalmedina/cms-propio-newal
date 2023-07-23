<?php

namespace App\Http\Controllers;

use App\Exports\CentersExport;
use App\Http\Requests\AdminCenterRequest;
use App\Models\Center;
use App\Models\Municipio;
use App\Models\PermissionsTree;
use App\Models\Province;
use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use App\Services\StoragePathWork;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

use Maatwebsite\Excel\Facades\Excel;

class AdminCenterController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAbleTo('admin-centers')) {
            app()->abort(403);
        }

        $pageTitle = trans('centers/admin_lang.centers');
        $title = trans('centers/admin_lang.list');


        return view('centers.admin_index', compact('pageTitle', 'title'));
    }

    public function create()
    {
        if (!auth()->user()->isAbleTo('admin-centers-create')) {
            app()->abort(403);
        }
        $pageTitle = trans('centers/admin_lang.new');
        $title = trans('centers/admin_lang.list');
        $center = new Center();
        $tab = 'tab_1';

        $provincesList = Province::active()->get();
        $municipiosList = Municipio::active()->where("province_id", $center->province_id)->get();

        return view('centers.admin_edit', compact('pageTitle', 'title', "center", "provincesList", 'municipiosList'))
            ->with('tab', $tab);
    }

    public function store(AdminCenterRequest $request)
    {
        if (!auth()->user()->isAbleTo('admin-centers-create')) {
            app()->abort(403);
        }

        try {
            DB::beginTransaction();

            $center = new Center();

            $this->saveCenter($center, $request);

            DB::commit();
            toastr()->success(trans('general/admin_lang.save_ok'));
            return redirect()->route('admin.centers.edit', [$center->id]); // ->with('success', trans('general/admin_lang.save_ok'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();

            return redirect('admin/centers/create')
                ->with('error', trans('general/admin_lang.save_ko') . ' - ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        if (!auth()->user()->isAbleTo('admin-centers-update')) {
            app()->abort(403);
        }
        $center = Center::find($id);

        if (empty($center)) {
            app()->abort(404);
        }

        $pageTitle = trans('centers/admin_lang.edit');
        $title = trans('centers/admin_lang.list');
        $tab = 'tab_1';
        $provincesList = Province::active()->get();
        $municipiosList = Municipio::active()->where("province_id", $center->province_id)->get();


        return view('centers.admin_edit', compact('pageTitle', 'title', "center", 'provincesList', 'municipiosList'))
            ->with('tab', $tab);
    }

    public function update(AdminCenterRequest $request, $id)
    {
        if (!auth()->user()->isAbleTo('admin-centers-update')) {
            app()->abort(403);
        }

        try {
            DB::beginTransaction();

            $center = Center::find($id);

            $this->saveCenter($center, $request);

            DB::commit();
            toastr()->success(trans('general/admin_lang.save_ok'));
            return redirect()->route('admin.centers.edit', [$center->id]); // ->with('success', trans('general/admin_lang.save_ok'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();

            return redirect('admin/centers/create/' . $center->id)
                ->with('error', trans('general/admin_lang.save_ko') . ' - ' . $e->getMessage());
        }
    }



    public function getData()
    {
        if (!auth()->user()->isAbleTo('admin-centers-list')) {
            app()->abort(403);
        }
        $query = Center::select([
            'centers.active',
            'centers.id',
            'centers.name',
            'centers.image',
            'centers.default',
            'centers.phone',
            'centers.email',
            'provinces.name as province',
            'municipios.name as municipio',
        ])
            ->leftJoin("provinces", "centers.province_id", "=", "provinces.id")
            ->leftJoin("municipios", "centers.municipio_id", "=", "municipios.id");

        $table = DataTables::of($query);

        $table->editColumn('active', function ($data) {
            $permision = "";
            if (!auth()->user()->isAbleTo('admin-centers-update')) {
                $permision = "disabled";
            }

            $state = $data->active ? "checked" : "";

            return  '<div class="form-check form-switch ">
                <input class="form-check-input" nclick="changeState(' . $data->id . ')" ' . $state . '  ' . $permision . '  value="1" name="active" type="checkbox" id="active">
            </div>';
        });

        $table->editColumn('image', function ($data) {
            if (empty($data->image)) {
                return "";
            }

            return  '<center><img width="40" class="rounded-circle" src="' . url('admin/centers/get-image/' . $data->image) . '" alt="imagen"> </center>';
        });
        $table->editColumn('default', function ($data) {

            if ($data->default) {
                return '<center><i class="fa fa-check text-success" aria-hidden="true"></i></center>';
            }
            return '<center><i class="fa fa-times text-danger" aria-hidden="true"></i></center>';
        });

        $table->editColumn('actions', function ($data) {
            $actions = '';
            if (auth()->user()->isAbleTo("admin-centers-update")) {
                $actions .= '<a  class="btn btn-info btn-sm" href="' . route('admin.centers.edit', $data->id) . '" ><i
                class="fa fa-marker fa-lg"></i></a> ';
            }

            if (auth()->user()->isAbleTo("admin-centers-delete")) {

                $actions .= '<button class="btn btn-danger btn-sm" onclick="javascript:deleteElement(\'' .
                    url('admin/centers/' . $data->id) . '\');" data-content="' .
                    trans('general/admin_lang.borrar') . '" data-placement="left" data-toggle="popover">
                        <i class="fa fa-trash" aria-hidden="true"></i></button>';
            }

            return $actions;
        });

        $table->removeColumn('id');
        $table->rawColumns(['actions', 'active', 'image', 'default']);
        return $table->make();
    }

    public function destroy($id)
    {
        // Si no tiene permisos para modificar lo echamos
        if (!auth()->user()->isAbleTo('admin-centers-delete')) {
            app()->abort(403);
        }
        $center = Center::find($id);
        if (empty($center)) {
            app()->abort(404);
        }
        $myServiceSPW = new StoragePathWork("centers");

        if (!empty($center->image)) {
            $myServiceSPW->deleteFile($center->image, '');
            $center->image = "";
        }
        $center->delete();

        return response()->json(array(
            'success' => true,
            'msg' => trans("general/admin_lang.delete_ok"),
        ));
    }

    public function changeState($id)
    {
        if (!auth()->user()->isAbleTo('admin-centers-update')) {
            app()->abort(403);
        }

        $center = Center::find($id);

        if (!empty($center)) {
            $center->active = !$center->active;
            return $center->save() ? 1 : 0;
        }

        return 0;
    }

    public function editAditionalInfo($id)
    {
        if (!auth()->user()->isAbleTo('admin-centers-update')) {
            app()->abort(403);
        }

        $center = Center::find($id);
        if (is_null($center)) {
            app()->abort(500);
        }
        $pageTitle = trans('centers/admin_lang.edit');
        $title = trans('centers/admin_lang.list');


        $tab = "tab_2";
        return view('centers.admin_edit_aditional_info', compact(
            'pageTitle',
            'title',
            "center"
        ))
            ->with('tab', $tab);
    }

    public function updateAditionalInfo(Request $request, $id)
    {

        if (!auth()->user()->isAbleTo('admin-centers-update')) {
            app()->abort(403);
        }

        $center = Center::find($id);

        if (is_null($center)) {
            app()->abort(500);
        }

        try {
            DB::beginTransaction();
            $center->specialities = $request->input('specialities');
            $center->schedule = $request->input('schedule');

            $center->save();
            DB::commit();
            // Y Devolvemos una redirección a la acción show para mostrar el usuario
            toastr()->success(trans('general/admin_lang.save_ok'));
            return redirect()->to('/admin/centers/aditional-info/' . $center->id); // ->with('success', trans('general/admin_lang.save_ok'));
        } catch (\PDOException $e) {
            DB::rollBack();
            dd($e);
            toastr()->error(trans('general/admin_lang.save_ko'));
            return redirect()->to('/admin/centers/aditional-info/' . $center->id);
            // ->with('error', trans('general/admin_lang.save_ko'));
        }
    }

    public function getImage($photo)
    {
        $myServiceSPW = new StoragePathWork("centers");
        return $myServiceSPW->showFile($photo, '/centers');
    }

    public function deleteImage($id)
    {
        $myServiceSPW = new StoragePathWork("centers");
        $center = Center::find($id);

        if (!empty($center->image)) {
            $myServiceSPW->deleteFile($center->image, '');
            $center->image = "";
        }
        $center->save();

        return response()->json(array(
            'success' => true,
            'msg' => trans("general/admin_lang.delete_ok"),
        ));
    }

    public function exportExcel()
    {
        if (!auth()->user()->isAbleTo('admin-centers-list')) {
            app()->abort(403);
        }
        $query = Center::select([
            'centers.active',
            'centers.id',
            'centers.name',
            'centers.image',
            'centers.default',
            'centers.phone',
            'centers.email',
            'centers.address',
            'centers.schedule',
            'centers.specialities',
            'provinces.name as province',
            'municipios.name as municipio',
        ])
            ->leftJoin("provinces", "centers.province_id", "=", "provinces.id")
            ->leftJoin("municipios", "centers.municipio_id", "=", "municipios.id");
        return Excel::download(new CentersExport($query), trans('centers/admin_lang.centers') . Carbon::now()->format("dmYHis") . '.xlsx');
    }


    private function saveCenter($center, $request)
    {
        $center->name = $request->input('name');
        $center->phone = $request->input('phone');
        $center->email = $request->input('email');
        $center->province_id = $request->input('province_id');
        $center->municipio_id = $request->input('municipio_id');
        $center->address = $request->input('address');
        $center->default = $request->input('default', 0);
        $center->active = $request->input('active', 0);

        $image = $request->file('image');

        if (!is_null($image)) {
            $myServiceSPW = new StoragePathWork("centers");

            if (!empty($center->image)) {
                $myServiceSPW->deleteFile($center->image, '');
                $center->image = "";
            }

            $filename = $myServiceSPW->saveFile($image, '');
            $center->image = $filename;
        }

        if ($request->input('default')) {
            DB::table('centers')
                ->update([
                    'default' => 0
                ]);
            $center->default = $request->input('default');
        }

        $center->save();
    }
}
