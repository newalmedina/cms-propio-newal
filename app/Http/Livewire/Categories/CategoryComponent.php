<?php

namespace App\Http\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryComponent extends Component
{
    use WithPagination;
    public  $name, $active, $description, $category_id, $delete_id;
    public $view = 'create';
    public $title = '';

    public function __construct()
    {
        $this->title =  trans('categories/admin_lang.new');
    }

    public function render()
    {
        if (!auth()->user()->isAbleTo('admin-categories')) {
            app()->abort(403);
        }
        $categories = Category::orderBy('id', 'desc')->paginate(10);
        return view('livewire.categories.category-component', compact("categories"));
    }


    public function save()
    {
        if (!auth()->user()->isAbleTo('admin-categories-create')) {
            app()->abort(403);
        }
        $this->validate([
            'name' => 'required',
            'description' => 'required',
            'active' => 'required'
        ]);

        Category::create([
            'name'        => $this->name,
            'description' => $this->description,
            'active'    => $this->active
        ]);
        session()->flash('success',  trans('general/admin_lang.save_ok'));
        $this->reset();
    }

    public function create()
    {
        if (!auth()->user()->isAbleTo('admin-categories-create')) {
            app()->abort(403);
        }
        $Category = new Category();

        $this->category_id = $Category->id;
        $this->name = $Category->name;
        $this->description = $Category->description;
        $this->active = $Category->active;
        $this->view = 'create';
        $this->title =  trans('categories/admin_lang.new');
    }
    public function edit($id)
    {
        if (!auth()->user()->isAbleTo('admin-categories-update')) {
            app()->abort(403);
        }

        $Category = Category::find($id);
        $this->category_id = $Category->id;
        $this->name = $Category->name;
        $this->description = $Category->description;
        $this->active = $Category->active;
        $this->view = 'edit';
        $this->title =  trans('categories/admin_lang.edit');
    }

    public function update()
    {
        if (!auth()->user()->isAbleTo('admin-categories-update')) {
            app()->abort(403);
        }
        $this->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $Category = Category::find($this->category_id);
        $Category->update([
            'name'        => $this->name,
            'description' => $this->description,
            'active'    => $this->active
        ]);
        session()->flash('success',  trans('general/admin_lang.save_ok'));
        $this->reset();
    }

    public function deleteId($id)
    {
        $this->delete_id = $id;
    }

    public function delete()
    {
        if (!auth()->user()->isAbleTo('admin-categories-delete')) {
            app()->abort(403);
        }
        Category::destroy($this->delete_id);
        session()->flash('success',  trans('general/admin_lang.delete_ok'));
    }
}
