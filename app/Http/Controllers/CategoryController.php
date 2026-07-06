<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    /**
     * Store a newly created category linked to the authenticated user.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $request->user()->categories()->create([
            'name' => $request->validated('name'),
        ]);

        return redirect()->route('tasks.index')->with('success', 'Category added.');
    }
}