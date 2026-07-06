<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $request->user()->categories()->create([
            'name' => $request->validated('name'),
        ]);

        return redirect()->route('tasks.index')->with('success', 'Category added.');
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $this->authorize('update', $category);

        $category->update([
            'name' => $request->validated('name'),
        ]);

        return redirect()->route('tasks.index')->with('success', 'Category renamed.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->authorize('delete', $category);

        $category->delete();

        return redirect()->route('tasks.index')->with('success', 'Category deleted.');
    }
}