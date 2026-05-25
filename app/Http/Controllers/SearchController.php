<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
        ]);

        $searchTerm = trim((string) ($validated['q'] ?? ''));

        if ($searchTerm === '') {
            return redirect()->route('shop');
        }

        $products = $this->searchProducts($searchTerm)
            ->with(['category', 'defaultVariant', 'variants', 'yarnColors'])
            ->latest()
            ->get();

        return view('user.search', [
            'searchTerm' => $searchTerm,
            'products' => $products,
        ]);
    }

    public function suggestions(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
        ]);

        $searchTerm = trim((string) ($validated['q'] ?? ''));

        if (mb_strlen($searchTerm) < 2) {
            return response()->json([
                'data' => [],
            ]);
        }

        $products = $this->searchProducts($searchTerm)
            ->with('category')
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'data' => $products->map(static function (Product $product): array {
                return [
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'image_url' => $product->image_url,
                    'category_name' => $product->category?->name,
                    'price' => number_format((float) $product->price, 2),
                    'url' => route('product.show', $product->slug),
                ];
            }),
        ]);
    }

    private function searchProducts(string $searchTerm)
    {
        $numericTerm = is_numeric($searchTerm) ? (float) $searchTerm : null;

        return Product::query()
            ->where('is_active', true)
            ->where(function ($query) use ($searchTerm, $numericTerm): void {
                $query->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('slug', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('category', function ($categoryQuery) use ($searchTerm): void {
                        $categoryQuery->where('name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('slug', 'like', '%' . $searchTerm . '%');
                    });

                if ($numericTerm !== null) {
                    $query->orWhere('price', $numericTerm);
                }
            });
    }
}
