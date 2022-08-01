@extends('users.admin_users_layout')


@section('tab_head')
   
@stop

@section('tab_breadcrumb')
    <li class="breadcrumb-item active"><a href="#">{{ $pageTitle }}</a></li>
@stop

@section('tab_content_1')

<div class="row">
    
    <div class="col-12">
        <form id="formData" action="{{ route("admin.users.update",$user->id) }}" method="post"  novalidate="false">
            @csrf       
            @method('patch')    
            <div class="card-body">
                <div class="row form-group mb-3">
                    <div class="col-12 col-md-6">
                     
                        <div class="form-group">
                            <label for="first_name"> {{ trans('users/admin_lang.fields.first_name') }}</label><span class="text-danger">*</span>
                            <input value="{{$user->userProfile->first_name }}" type="text" class="form-control" name="user_profile[first_name]"  placeholder="{{ trans('users/admin_lang.fields.first_name_helper') }}">
                        </div>
                    </div>    
                    
                    <div class="col-12 col-md-6">
                     
                        <div class="form-group">
                            <label for="last_name"> {{ trans('users/admin_lang.fields.last_name') }}</label><span class="text-danger">*</span>
                            <input value="{{$user->userProfile->last_name }}" type="text" class="form-control" name="user_profile[last_name"]  placeholder="{{ trans('users/admin_lang.fields.last_name_helper') }}">
                        </div>
                    </div>     
                </div>

                <div class="row form-group mb-3">
                    <div class="col-12">
                     
                        <div class="form-group">
                            <label for="email"> {{ trans('users/admin_lang.fields.email') }}</label><span class="text-danger">*</span>
                            <input value="{{ $user->email }}" type="text" class="form-control" name="email"  placeholder="{{ trans('users/admin_lang.fields.email_helper') }}">
                        </div>
                    </div>                    
                </div>
                <div class="row form-group mb-3">
                    <div class="col-12 col-md-6">
                     
                        <div class="form-group">
                            <label for="password"> {{ trans('users/admin_lang.fields.password') }}</label><span class="text-danger">*</span>
                            <input value="" type="text" class="form-control" name="password"  placeholder="{{ trans('users/admin_lang.fields.password_helper') }}">
                        </div>
                    </div>  
                    <div class="col-12 col-md-6">
                     
                        <div class="form-group">
                            <label for="password_confirm"> {{ trans('users/admin_lang.fields.password_confirm') }}</label><span class="text-danger">*</span>
                            <input value="" type="text" class="form-control" name="password_confirm"  placeholder="{{ trans('users/admin_lang.fields.password_confirm_helper') }}">
                        </div>
                    </div>                    
                </div>
                

                
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-success">{{ trans('general/admin_lang.save') }}</button>   
            </div>
        </form>
    </div>
</div>
@endsection

@section("tab_foot")
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

{!! JsValidator::formRequest('App\Http\Requests\AdminUserRequest')->selector('#formData') !!}
@stop