@extends('layouts.admin.default')
@section('title')
    @parent {{ $pageTitle }}
@stop
@section('tab_head')

@stop

@section('breadcrumb')
<li><span>{{ $pageTitle }}</span></li>
@stop

@section('content')


<section role="main" class="content-body card-margin">
    <div class="mt-4">

        @include('layouts.admin.includes.errors')

    </div>
    
    <!-- start: page -->
   
        <div class="row">
            <div class="col-12 col-md-3">
                <section class="card">
                   
                    <div class="card-body">
                        
                        <div class="thumb-info mb-3">
                            <div id="fileOutput">
                                @if($user->userProfile->photo!='')
                                    <img src='{{ url('admin/profile/getphoto/'.$user->userProfile->photo) }}' id='image_ouptup' class="rounded img-fluid" alt="{{ Auth::user()->userProfile->fullName }}">
                                @else
                                    <img src="{{ asset("/assets/front/img/!logged-user.jpg") }}" class="rounded img-fluid" alt="{{ Auth::user()->userProfile->fullName }}">
                                @endif
                            </div>
    
    
                            <div class="thumb-info-title">
                                <span class="thumb-info-inner">{{ Auth::user()->userProfile->fullName }}</span>
                                <span class="thumb-info-type"> ROl administrador{{-- implode(",",Auth::user()->roles->pluck('display_name')->toArray()) --}}</span>
                            </div>
                        </div>
    
                        <div id="remove" class="text-danger" style="@if($user->userProfile->photo=='') display: none; @endif cursor: pointer; text-align: center;"><i class="fa fa-times" aria-hidden="true"></i> {{ trans('profile/admin_lang.quit_image') }} </div>
    
    
                        <hr class="dotted short">
    
                        <h5 class="mb-2 mt-3">  {{ trans('profile/admin_lang.acerca_de') }}</h5>
                        <p class="text-2">
                            {{ trans('profile/admin_lang.registered_at') }} {{ Auth::user()->createdAtFormatted }}
                        </p>
                    </div>
                </section>
            </div>
            <div class="col-12 col-md-9">
                <section class="card">
                    <header class="card-header">                        
                        <h2 class="card-title">{{ $title }}</h2>
                    </header>
                    <form id="formData" action="{{ route("admin.updateProfile") }}" method="Post" enctype="multipart/form-data" novalidate="false">
                        @csrf
                        <input type="hidden" name="delete_photo" id="delete_photo">
                        <div class="card-body">
                            <p>{{ trans('profile/admin_lang.perfil_usuario_desc') }}</p>

                            <div class="row form-group">
                                <div class="col-lg-6">
                                 
                                    <div class="form-group">
                                        <label for="first_name"> {{ trans('profile/admin_lang.fields.first_name') }}<span class="text-danger">*</span></label>
                                        <input value="{{ $user->userProfile->first_name }}" type="text" class="form-control" name="user_profile[first_name]"  placeholder="{{ trans('profile/admin_lang.fields.first_name_helper') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="last_name"> {{ trans('profile/admin_lang.fields.last_name') }}<span class="text-danger">*</span></label>
                                        <input  value="{{ $user->userProfile->last_name }}"  type="text" class="form-control" name="user_profile[last_name]"  id="last_name" placeholder="{{ trans('profile/admin_lang.fields.last_name_helper') }}">
                                    </div>
                                </div>
                            </div>

                        

                            <div class="row form-group">                         
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="profile_image"> {{ trans('profile/admin_lang.fields.profile_image') }}</label>
                                        <input type="file" class="form-control d-none"  accept="image/*"name="profile_image" id="profile_image" style="opacity: 0; width: 0;">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="nombrefichero" readonly>
                                            <span class="input-group-append">
                                                <button id="btnSelectImage" class="btn btn-primary" type="button">{{ trans('profile/admin_lang.fields.search_image') }}</button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h4 class="mb-3">{{ trans('profile/admin_lang.access_data') }}</h4>
                            <div class=" form-group">
                                <label for="email"> {{ trans('profile/admin_lang.fields.email') }}<span class="text-danger">*</span></label>
                                <input  value="{{ $user->email }}"  type="text" class="form-control" name="email" id="email" placeholder="{{ trans('profile/admin_lang.fields.email_helper') }}">
                            </div>
                            <div class="row form-group">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="password"> {{ trans('profile/admin_lang.fields.password') }}</label>
                                        <input type="password" class="form-control" name="password" id="password" placeholder="{{ trans('profile/admin_lang.fields.password_helper') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="password_confirm"> {{ trans('profile/admin_lang.fields.password_confirm') }}</label>
                                        <input type="password" class="form-control" name="password_confirm" id="password_confirm" placeholder="{{ trans('profile/admin_lang.fields.password_confirm_helper') }}">
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-success">{{ trans('general/admin_lang.save') }}</button>   
                        </div>
                    </form>
                </section>
            </div>
     
        </div>
    </div>
    <!-- end: page -->
</section>
@endsection
@section("foot_page")
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

<script>

      $(document).ready(function() {
            $("#btnSelectImage").click(function() {
                $('#profile_image').trigger('click');
            });

            $("#profile_image").change(function(){
                getFileName();
                readURL(this);
            });

            $("#remove").click(function() {
                $('#nombrefichero').val('');
                $('#profile_image').val("");
                $('#fileOutput').html('<img src="{{ asset("/assets/front/img/!logged-user.jpg") }}" class="rounded img-fluid" alt="{{ Auth::user()->userProfile->fullName }}">');
                $("#remove").css("display","none");
                $("#delete_photo").val('1');
            });
      });
      function getFileName() {
            $('#nombrefichero').val($('#profile_image')[0].files[0].name);
            $("#delete_photo").val('1');
            $("#contenedor-remove").css("display","");
        }
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#fileOutput').html("<img src='"+e.target.result+"' id='image_ouptup' width='100%' alt=''>");
                    $("#remove").css("display","block");
                  //  $('#image_ouptup').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
</script>

{!! JsValidator::formRequest('App\Http\Requests\AdminProfileRequest')->selector('#formData') !!}

@stop