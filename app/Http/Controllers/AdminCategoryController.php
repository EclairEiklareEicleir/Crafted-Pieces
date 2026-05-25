<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminCategoryController extends Controller
{
    private const CATEGORY_IMAGE_MAX_KB = 10240;

    public function store(Request $request)
    {
        $request->validate(
            $this->categoryValidationRules(),
            $this->categoryValidationMessages()
        );

        $slug = Str::slug($request->name);

        $originalSlug = $slug;
        $counter = 1;

        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        Category::create([
            'name' => $request->name,
            'slug' => $slug,
            'image_path' => $request->hasFile('image')
                ? $request->file('image')->store('categories', 'public')
                : null,
        ]);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Category created successfully.');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate(
            $this->categoryValidationRules(),
            $this->categoryValidationMessages()
        );

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

        $imagePath = $category->image_path;

        if ($request->hasFile('image')) {
            if ($category->image_path) {
                Storage::disk('public')->delete($category->image_path);
            }

            $imagePath = $request->file('image')->store('categories', 'public');
        }

        $category->update([
            'name' => $request->name,
            'slug' => $slug,
            'image_path' => $imagePath,
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

        if ($category->image_path) {
            Storage::disk('public')->delete($category->image_path);
        }

        $category->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Category deleted successfully.');
    }

    private function categoryValidationRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:' . self::CATEGORY_IMAGE_MAX_KB,
        ];
    }

    private function categoryValidationMessages(): array
    {
        $maxMb = (int) (self::CATEGORY_IMAGE_MAX_KB / 1024);

        return [
            'image.mimes' => 'The image must be a file of type: jpg, jpeg, png, webp.',
            'image.max' => 'The image field must not be greater than ' . $maxMb . ' MB.',
        ];
    }
}