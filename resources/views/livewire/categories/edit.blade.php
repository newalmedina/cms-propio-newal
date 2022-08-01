

<!-- Button trigger modal -->
<div class="text-end">
  @if(Auth::user()->isAbleTo("admin-categories-create"))
    <button type="button" wire:click='create()' class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#categoryModal">
      {{ trans('categories/admin_lang.new') }}
    </button>
  @endif
</div>


<!-- Modal -->
<div class="modal fade successSaved" id="categoryModal" wire:ignore.self data-bs-backdrop="static" data-bs-keyboard="true"  aria-labelledby="staticBackdropLabel">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="staticBackdropLabel">{{ $title }}</h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">     
             
        @include('layouts.admin.includes.errors')
        @include('livewire.categories.form')              
      </div>
      <div class="modal-footer d-flex justify-content-between">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('general/admin_lang.close') }}</button>
        <button @if ($view=="create") wire:click="save()" @else  wire:click="update()"  @endif class="btn btn-success">{{ trans('general/admin_lang.save') }}</button>
      
      </div>
    </div>
  </div>
</div>









