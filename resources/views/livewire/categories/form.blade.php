<div class="form-group">
    <label for="exampleInputPassword1">{{ trans('categories/admin_lang.fields.name') }}</label>
    <input type="text" wire:model="name" class="form-control input-sm"  placeholder="Nombre">
    @error('name') <span>{{ $message }}</span> @enderror
  </div>
  <div class="form-group">
      <label for="exampleInputPassword1">{{ trans('categories/admin_lang.fields.description') }}</label>
      <textarea wire:model="description" class="form-control input-sm"  placeholder="Descripción" cols="30" rows="5"></textarea>

      @error('description') <span>{{ $message }}</span> @enderror
  </div>
  <div class="form-group row">
      <label class="col-12" for="exampleInputPassword1">{{ trans('categories/admin_lang.fields.active') }}</label>
       <div class="col-12">
         <input class=""   wire:model="active" name="active"      type="radio"  value="1">{{ trans('general/admin_lang.yes') }}
        <input class=""   wire:model="active" name="active" type="radio"  value="0">{{ trans('general/admin_lang.no') }}
       </div>
      @error('name') <span>{{ $active }}</span> @enderror
  </div>    