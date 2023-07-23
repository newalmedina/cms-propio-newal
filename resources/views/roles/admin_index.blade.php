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
   
        <div class="row">
            <div class="col">
                <section class="card card-featured-top card-featured-primary">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>

                        <h2 class="card-title">{{ $title }}</h2>
                    </header>
                    <div class="card-body">      
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ trans('roles/admin_lang.fields.id') }}</th>
                                    <th>{{ trans('roles/admin_lang.fields.active') }}</th>
                                    <th>{{ trans('roles/admin_lang.fields.display_name') }}</th>
                                    <th>{{ trans('roles/admin_lang.fields.description') }}</th>
                                    <th width="150">{{ trans('general/admin_lang.actions') }}</th>
                                 
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $role->id }}</td>
                                    <td>
                                        @if ($role->active)
                                            <span class="badge bg-success">{{ trans('general/admin_lang.active') }}</span>
                                            @else
                                            <span class="badge bg-danger">{{ trans('general/admin_lang.inactive') }}</span>                    
                                        @endif                   
                                    </td>
                                    <td>{{ $role->display_name }}</td>
                                    <td>{{ $role->description }}</td>               
                                    <td>
                                        <a  class="btn btn-info" href="{{ route('admin.roles.edit',$role->id) }}" ><i
                                            class="fa fa-marker fa-lg"></i></a>    
                                          
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    <!-- end: page -->
</section>   
@endsection
@section('foot_page')
<script type="text/javascript" src="{{ asset('/assets/admin/vendor/jquery-bonsai/js/jquery.bonsai.js')}}"></script>
<script type="text/javascript" src="{{ asset('/assets/admin/vendor/jquery-qubit/js/jquery.qubit.js')}}"></script>

<script>
     $(document).ready(function() {

            $('#checkboxes').bonsai({
                expandAll: false,
                checkboxes: true
            });

    });
</script>
@stop