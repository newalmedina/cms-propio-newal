<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminProfileRequest;
use App\Models\User;
use App\Services\StoragePathWork;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserProfileController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //Obtengo la información del usuario para pasarsela al formulario
        $user = User::with('userProfile')->find(auth()->user()->id);

        $pageTitle =  trans('profile/admin_lang.my_profile');
        $title =  trans('profile/admin_lang.personal_information');
        return view(
            'profile.admin_edit',
            compact(
                'pageTitle',
                'title',
                'user',
            )
        );
    }

    public function store(AdminProfileRequest $request)
    {

        // Id actual
        $idprofile = auth()->user()->id;

        // Creamos un nuevo objeto para nuestro nuevo usuario
        $user = User::with('userProfile')->find($idprofile);

        // Si el usuario no existe entonces lanzamos un error 404 :(
        if (is_null($user)) {
            app()->abort(404);
        }

        $myServiceSPW = new StoragePathWork("users");

        // Si la data es valida se la asignamos al usuario

        $user->email = $request->input('email');
        if (!empty($request->input('password'))) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->userProfile->first_name = $request->input('user_profile.first_name');
        $user->userProfile->last_name = $request->input('user_profile.last_name');

        if ($request->input("delete_photo") == '1') {
            $myServiceSPW->deleteFile($user->userProfile->photo, '');
            $user->userProfile->photo = "";
        }

        try {
            DB::beginTransaction();

            $file = $request->file('profile_image');

            if (!is_null($file)) {
                $myServiceSPW->deleteFile($user->userProfile->photo, '');

                $filename = $myServiceSPW->saveFile($file, '');

                $user->userProfile->photo = $filename;
            }
            $user->push();
            // Redirect to the new user page
            DB::commit();


            // Y Devolvemos una redirección a la acción show para mostrar el usuario
            return redirect('admin/profile')->with('success-alert', trans('general/admin_lang.save_ok'));
        } catch (\PDOException $e) {
            // Woopsy
            dd($e);
            DB::rollBack();
            toastr()->error(trans('general/admin_lang.save_ko'));
            return redirect('profile'); // ->with('error-alert', trans('general/admin_lang.save_ko') . ' - ' . $e->getMessage());
        }
    }
    public function getPhoto($photo)
    {
        $myServiceSPW = new StoragePathWork("users");
        return $myServiceSPW->showFile($photo, '/users');
    }
}
