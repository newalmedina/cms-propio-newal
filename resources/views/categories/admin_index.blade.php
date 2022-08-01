@extends('layouts.admin.default')
@section('title')
    @parent {{ $pageTitle }}
@stop


@section('breadcrumb')
<li><span>{{ $title }}</span></li>
@stop

@section('content')
    
    <section role="main" class="content-body card-margin">
      
        <!-- start: page -->
       
            <div class="row">
                <div class="col">
                    <section class="card">
                        <header class="card-header">
                            <div class="card-actions">
                                <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                                <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                            </div>

                            <h2 class="card-title">{{ $title }}</h2>
                        </header>
                        <div class="card-body">s                            
                            @livewire('categories.category-component')
                        </div>
                    </section>
                </div>
            </div>
        <!-- end: page -->
    </section>
@endsection