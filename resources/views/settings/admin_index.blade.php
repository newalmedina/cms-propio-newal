@extends('layouts.admin.default')
@section('title')
    @parent {{ $pageTitle }}
@stop
@section('head_page')
<link href="{{ asset('/assets/admin/vendor/jquery-bonsai/css/jquery.bonsai.css')}}" rel="stylesheet" />
    
@stop

@section('breadcrumb')
<li><span>{{ $title }}</span></li>
@stop

@section('content')
<section role="main" class="content-body card-margin">
      
    <!-- start: page -->
   
    <div class="row mt-5">
        <div class="col-12 col-md-3">
            <section class="card">
            
                <div class="card-body">
                    
                    <div class="thumb-info mb-3">
                        <div id="fileOutput">
                            {{-- @if($user->userProfile->photo!='')
                                <img src='{{ url('admin/profile/getphoto/'.$user->userProfile->photo) }}' id='image_ouptup' class="rounded img-fluid" alt="{{ Auth::user()->userProfile->fullName }}">
                            @else
                                <img src="{{ asset("/assets/front/img/!logged-user.jpg") }}" class="rounded img-fluid" alt="{{ Auth::user()->userProfile->fullName }}">
                            @endif --}}
                        </div>
                    </div>
                    
                </div>
            </section>
        </div>
        <div class="col-12 col-md-9">
            <div class="tabs tabs-primary">
                <ul class="nav nav-tabs" id="custom-tabs">
            
                    <li class="nav-item ">
                        <a id="tab_1" class="nav-link" data-bs-target="#tab_1-1" data-bs-toggle="tabajax"
                            href="#"
                            data-target="#tab_1-1" aria-controls="tab_1-1" aria-selected="true">
                            {{ trans('settings/admin_lang.settings') }}
                        </a>
                    </li>
            
                
                  
                </ul>
                <div class="tab-content" id="tab_tabContent">
                    <div id="tab_1-1" class="tab-pane  active ">

                        <form id="formData" enctype="multipart/form-data" action=" method="post"  novalidate="false">
                            @csrf       
                             
                                @method('post')
                          
                              
                            <div class="card-body">
                                <div class="row form-group mb-3">
                                    <div class="col-12">
                                     
                                        <div class="form-group">
                                            <label for="name"> {{ trans('centers/admin_lang.fields.name') }}</label><span class="text-danger">*</span>
                                            <input value="{{!empty($center->name) ? $center->name :null }}" type="text" class="form-control" name="name"  placeholder="{{ trans('centers/admin_lang.fields.name_helper') }}">
                                        </div>
                                    </div>      
                                </div>
                                {{-- <div class="row form-group mb-3"">                         
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="image"> {{ trans('centers/admin_lang.fields.image') }}</label>
                                            <input type="file" accept="image/*" class="form-control d-none" name="image" id="center_image" style="opacity: 0; width: 0;">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="nombrefichero" readonly>
                                                <span class="input-group-append">
                                                    <button id="btnSelectImage" class="btn btn-primary" type="button">{{ trans('profile/admin_lang.fields.search_image') }}</button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="row form-group mb-3">
                                    <div class="col-12 col-md-6">                     
                                        <div class="form-group">
                                            <label for="phone"> {{ trans('centers/admin_lang.fields.phone') }}</label><span class="text-danger">*</span>
                                            <input value="{{!empty($center->phone) ? $center->phone :null }}" type="text" class="form-control" name="phone"  placeholder="{{ trans('centers/admin_lang.fields.phone_helper') }}">
                                        </div>
                                    </div>    
                                    <div class="col-12 col-md-6">                     
                                        <div class="form-group">
                                            <label for="email"> {{ trans('centers/admin_lang.fields.email') }}</label><span class="text-danger">*</span>
                                            <input value="{{!empty($center->email) ? $center->email :null }}" type="text" class="form-control" name="email"  placeholder="{{ trans('centers/admin_lang.fields.email_helper') }}">
                                        </div>
                                    </div>                        
                                </div>
                
                             
                
                                <div class="row form-group mb-3">
                                    <div class="col-12 col-md-6">                     
                                        <div class="form-group">
                                            <label for="province_id"> {{ trans('centers/admin_lang.fields.province_id') }}</label><span class="text-danger">*</span>
                                            <select class="form-control select2" name="province_id" id="province_id">
                                                <option value="">{{ trans('centers/admin_lang.fields.province_id_helper') }}</option>   
                                                @foreach ($provincesList as $province)
                                                    <option value="{{ $province->id }}" @if($center->province_id ==$province->id) selected @endif>{{ $province->name }}</option>
                                                @endforeach 
                                            </select>    
                                        
                                        </div>
                                    </div>    
                                    <div class="col-12 col-md-6">                     
                                        <div class="form-group">
                                            <label for="municipio_id"> {{ trans('centers/admin_lang.fields.municipio_id') }}</label><span class="text-danger">*</span>
                                            <select class="form-control select2" name="municipio_id" id="municipio_id">
                                                <option value="">{{ trans('centers/admin_lang.fields.municipio_id_helper') }}</option>   
                                                @foreach ($municipiosList as $municipio)
                                                    <option value="{{ $municipio->id }}" @if($center->municipio_id ==$municipio->id) selected @endif>{{ $municipio->name }}</option>
                                                @endforeach 
                                            </select>    
                                        </div>
                                    </div>                        
                                </div>
                                <div class="row form-group mb-3">
                                    <div class="col-12">                     
                                        <div class="form-group">
                                            <label for="address"> {{ trans('centers/admin_lang.fields.address') }}</label><span class="text-danger">*</span>
                                            <input value="{{!empty($center->address) ? $center->address :null }}" type="text" class="form-control" name="address"  placeholder="{{ trans('centers/admin_lang.fields.address_helper') }}">
                                        </div>
                                    </div>                      
                                </div>
                                <div class="row form-group mb-3">
                                    <div class="col-12 col-md-6">                     
                                        <div class="form-group">
                                            <label for="active"> {{ trans('centers/admin_lang.fields.active') }}</label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input toggle-switch" @if($center->active==1) checked @endif value="1" name="active" type="checkbox" id="active">
                                            </div>                           
                                        </div>
                                    </div>                    
                                    <div class="col-12 col-md-6">                     
                                        <div class="form-group">
                                            <label for="default"> {{ trans('centers/admin_lang.fields.default') }}</label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input toggle-switch" @if($center->default==1) checked @endif value="1" name="default" type="checkbox" id="default">
                                            </div>                           
                                        </div>
                                    </div>                    
                                </div>                
                            </div>
                            <div class="card-footer row">
                                <div class="col-12  d-flex justify-content-between">
                
                                    <a href="{{ url('admin/centers') }}" class="btn btn-default">{{ trans('general/admin_lang.back') }}</a>
                                    <button type="submit" class="btn btn-success">{{ trans('general/admin_lang.save') }}</button>   
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end: page -->
</section>   
@endsection
@section('foot_page')
@stop