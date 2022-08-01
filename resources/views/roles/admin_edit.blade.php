@extends('roles.admin_role_layout')


@section('tab_head')
   
@stop

@section('tab_breadcrumb')
    <li class="breadcrumb-item active"><a href="#">{{ $pageTitle }}</a></li>
@stop

@section('tab_content_1')

<div class="row">
    <div class="col-12">
        <form id="formData" action="{{ route("admin.roles.update",$role->id) }}" method="post"  novalidate="false">
            @csrf       
            @method('patch')    
            <div class="card-body">
                <div class="row form-group mb-3">
                    <div class="col-lg-12">
                     
                        <div class="form-group">
                            <label for="display_name"> {{ trans('roles/admin_lang.fields.display_name') }}</label><span class="text-danger">*</span>
                            <input value="{{ $role->display_name }}" type="text" class="form-control" name="display_name"  placeholder="{{ trans('roles/admin_lang.fields.display_name_helper') }}">
                        </div>
                    </div>                    
                </div>
                <div class="row form-group">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="last_name"> {{ trans('roles/admin_lang.fields.description') }}</label>
                            <textarea class="form-control" name="description" id="description" disabled cols="30" rows="10">{{ $role->description }}</textarea>
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

{!! JsValidator::formRequest('App\Http\Requests\AdminRoleRequest')->selector('#formData') !!}
@stop