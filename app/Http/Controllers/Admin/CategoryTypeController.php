<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Models\CategoryType;
use App\Http\Requests\StoreCategoryTypeRequest;
use App\Http\Requests\UpdateCategoryTypeRequest;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CategoryTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:category.type list', ['only' => ['index']]);
        $this->middleware('can:category.type create', ['only' => ['create', 'store']]);
        $this->middleware('can:category.type edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:category.type delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $category_types = (new CategoryType)->newQuery();

        if (request()->has('search')) {
            $category_types->where('name', 'Like', '%'.request()->input('search').'%');
        }

        if (request()->query('sort')) {
            $attribute = request()->query('sort');
            $sort_order = 'ASC';
            if (strncmp($attribute, '-', 1) === 0) {
                $sort_order = 'DESC';
                $attribute = substr($attribute, 1);
            }
            $category_types->orderBy($attribute, $sort_order);
        } else {
            $category_types->latest();
        }

        $category_types = $category_types->paginate(100)->appends(request()->query());

        return Inertia::render('Admin/CategoryType/Index', [
            'category_types' => $category_types,
            'filters' => request()->all('search'),
            'can' => [
                'create' => Auth::user()->can('category.type create'),
                'edit' => Auth::user()->can('category.type edit'),
                'delete' => Auth::user()->can('category.type delete'),
                'manage' => Auth::user()->can('category.type list'),
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Admin/CategoryType/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreCategoryTypeRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCategoryTypeRequest $request)
    {
        CategoryType::create([
            'name' => $request->name,
            'machine_name' => $request->machine_name,
            'description' => $request->description,
        ]);

        return redirect()->route('category.index')
                        ->with('message', 'CategoryType created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Http\Models\CategoryType  $category
     * @return \Inertia\Response
     */
    public function edit(CategoryType $category)
    {
        return Inertia::render('Admin/CategoryType/Edit', [
            'category' => $category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateCategoryTypeRequest  $request
     * @param  \App\Http\Models\CategoryType  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCategoryTypeRequest $request, CategoryType $category)
    {
        $category->update($request->all());

        return redirect()->route('category.index')
                        ->with('message', 'CategoryType updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Models\CategoryType  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(CategoryType $category)
    {
        $category->delete();

        return redirect()->route('category.index')
                        ->with('message', __('CategoryType deleted successfully'));
    }
}
