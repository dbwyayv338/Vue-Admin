<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryItemRequest;
use App\Http\Requests\StoreMenuItemRequest;
use App\Http\Requests\UpdateCategoryItemRequest;
use App\Http\Requests\UpdateMenuItemRequest;
use App\Models\CategoryType;
use App\Models\CategoryItem;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CategoryItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:category.item list', ['only' => ['index', 'show']]);
        $this->middleware('can:category.item create', ['only' => ['create', 'store']]);
        $this->middleware('can:category.item edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:category.item delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index(CategoryType $category)
    {
        $items = (new CategoryItem)->toTree($category->id);

        return Inertia::render('Admin/CategoryItem/Index', [
            'category' => $category,
            'items' => $items,
            'can' => [
                'create' => Auth::user()->can('category.item create'),
                'edit' => Auth::user()->can('category.item edit'),
                'delete' => Auth::user()->can('category.item delete'),
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create(CategoryType $category)
    {
        $item_options = CategoryItem::selectOptions($category->id);
        return Inertia::render('Admin/CategoryItem/Create', [
            'category' => $category,
            'item_options' => $item_options
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreCategoryItemRequest  $request
     * @param CategoryType $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCategoryItemRequest $request, CategoryType $category)
    {
        $category->categories()->create($request->all());

        return redirect()->route('category.item.index', $category->id)
                        ->with('message', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param CategoryType $category
     * @return \Inertia\Response
     */
    public function edit(CategoryType $category, CategoryItem $item)
    {
        $item_options = CategoryItem::selectOptions($category->id, $item->parent_id ?? $item->id);
        return Inertia::render('Admin/CategoryItem/Edit', [
            'category' => $category,
            'item' => $item,
            'item_options' => $item_options
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateCategoryItemRequest  $request
     * @param CategoryType $category
     * @param CategoryItem $item
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCategoryItemRequest $request, CategoryType $category, CategoryItem $item)
    {
        $item->update($request->all());

        return redirect()->route('category.item.index', $category->id)
                        ->with('message', 'Category Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CategoryType $category
     * @param CategoryItem $item
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(CategoryType $category, CategoryItem $item)
    {
        $item->delete();

        return redirect()->route('category.item.index', $category->id)
                        ->with('message', __('Category deleted successfully'));
    }
}
