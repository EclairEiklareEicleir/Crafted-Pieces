<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $slug = Str::slug($request->name);

        $originalSlug = $slug;
        $counter = 1;

        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        Category::create([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Category created successfully.');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $slug = Str::slug($request->name);

        $originalSlug = $slug;
        $counter = 1;

        while (
            Category::where('slug', $slug)
                ->where('id', '!=', $category->id)
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $category->update([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Delete category
     */
    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return redirect()
                ->route('admin.products.index')
                ->with('error', 'Cannot delete category with existing products.');
        }

        $category->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Category deleted successfully.');
    }
}