<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('courses')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
        ]);

        $validated['slug'] = $this->generateUniqueSlug($validated['name']);
        $validated['is_storefront_category'] = true;

        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        // If the name changed, regenerate a unique slug
        if ($validated['name'] !== $category->name) {
            $validated['slug'] = $this->generateUniqueSlug($validated['name'], $category->id);
        }

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        // Block deletion if any product/course record is assigned
        if ($category->courses()->exists()) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete category because it has assigned products.');
        }

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }

    /**
     * Helper to generate unique slug, handling duplicate conflicts and blank results.
     */
    protected function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        
        // Handle blank result (e.g. name is just symbols)
        if (empty($slug)) {
            $slug = 'category-' . rand(100, 999);
        }
        
        $originalSlug = $slug;
        $count = 1;

        while (Category::where('slug', $slug)
            ->when($ignoreId, function ($query) use ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            })->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }
}
