<div>
   @include('layouts.admin.includes.modalDeleteConfirm')
   @include('layouts.admin.includes.success')
    <div class="row">
        <div class="col-12  mb-3">
            @include("livewire.categories.edit")
        </div>
    </div>
    <div class="row">
        @if(Auth::user()->isAbleTo("admin-categories-list"))
            <div class="col-12">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ trans('categories/admin_lang.fields.id') }}</th>
                            <th>{{ trans('categories/admin_lang.fields.active') }}</th>
                            <th>{{ trans('categories/admin_lang.fields.name') }}</th>
                            <th>{{ trans('categories/admin_lang.fields.description') }}</th>
                            <th width="150">{{ trans('general/admin_lang.actions') }}</th>
                        
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>
                                @if ($category->active)
                                    <span class="badge bg-success">{{ trans('general/admin_lang.active') }}</span>
                                    @else
                                    <span class="badge bg-danger">{{ trans('general/admin_lang.inactive') }}</span>                    
                                @endif                   
                            </td>
                            <td>{{ $category->name }}</td>
                            <td>{{ substr($category->description , 0, 100)}} <span class="text-muted">...</span></td>               
                            <td>
                                @if(Auth::user()->isAbleTo("admin-categories-update"))
                                    <button data-bs-toggle="modal" data-bs-target="#categoryModal" type="button" class="btn btn-info" wire:click='edit({{ $category->id }})'><i
                                        class="fa fa-marker fa-lg"></i></button>    
                                @endif
                                @if(Auth::user()->isAbleTo("admin-categories-delete"))
                                    <button type="button" class="btn btn-danger" 
                                    wire:click='deleteId({{ $category->id }})' data-bs-toggle="modal" data-bs-target="#modalConfirmDelete"><i
                                            class="fa fa-trash-alt fa-lg"></i></button> 
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-12 text-end">
                {{ $categories   ->links('pagination::Bootstrap-4') }}
            </div>
        @else
            <div class="text-center text-warning">
                <h3> {{ trans('general/admin_lang.dont_permissions') }}</h3>
            </div>
        @endif
    </div>
</div>

