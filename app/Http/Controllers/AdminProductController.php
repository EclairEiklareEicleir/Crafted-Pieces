<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\YarnColor;
use App\Support\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AdminProductController extends Controller
{
    private const IMAGE_MAX_KB = 5120;

    public function index()
    {
        return view('admin.products.index', [
            'categories' => Category::latest()->get(),
            'products' => Product::with(['category', 'defaultVariant', 'variants'])->latest()->get(),
        ]);
    }

    public function export(): Response
    {
        $products = Product::with(['category', 'variants'])->latest()->get();
        $rows = [];
        $totalStock = 0;
        $lowStockCount = 0;
        $outOfStockCount = 0;

        foreach ($products as $product) {
            $variants = $product->variants->isNotEmpty() ? $product->variants : collect([null]);

            foreach ($variants as $variant) {
                $stock = (int) ($variant?->stock ?? $product->stock ?? 0);

                if ($stock <= 0) {
                    $outOfStockCount++;
                } elseif ($stock <= 5) {
                    $lowStockCount++;
                }

                $totalStock += max(0, $stock);

                $rows[] = [
                    $product->name,
                    $product->category?->name,
                    $variant?->name ?? $product->name,
                    $variant?->yarn_color ?? '',
                    $variant?->size ?? '',
                    $variant?->material ?? '',
                    $variant?->design_style ?? '',
                    $variant?->set_quantity ?? '',
                    $variant?->packaging_option ?? '',
                    $variant?->sku ?? '',
                    (float) ($variant?->price ?? $product->price),
                    $stock,
                    $variant?->status ?? ($product->is_active ? 'active' : 'inactive'),
                    $variant ? $variant->availability_label : (($product->is_active && (int) $product->stock > 0) ? 'Available' : 'Out of Stock'),
                ];
            }
        }

        $columns = [
            'Product Name',
            'Category',
            'Variant Name',
            'Yarn Color',
            'Size',
            'Material',
            'Style',
            'Set Quantity',
            'Packaging',
            'SKU',
            'Price',
            'Stock',
            'Status',
            'Availability',
        ];

        $metadata = [
            'Generated Date' => now()->format('Y-m-d H:i:s'),
            'Total Products' => (string) $products->count(),
            'Total Variants' => (string) $products->sum(fn (Product $product) => $product->variants->count()),
            'Total Stock' => (string) $totalStock,
            'Low Stock Count' => (string) $lowStockCount,
            'Out of Stock Count' => (string) $outOfStockCount,
        ];

        $html = $this->buildExcelHtml(
            'Crafted Pieces Product Inventory Report',
            $metadata,
            $columns,
            $rows,
            [10]
        );

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="crafted_pieces_products_report_' . now()->format('Y-m-d') . '.xls"',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    public function create()
    {
        return view('admin.products.create', [
            'categories' => Category::all(),
            'product' => new Product(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateProductRequest($request);
        $slug = $this->uniqueSlug($validated['name']);

        DB::transaction(function () use ($request, $validated, $slug) {
            $imagePath = null;

            if ($request->hasFile('image')) {
                $imagePath = $this->storeFloatingProductImage($request);
            }

            $product = Product::create([
                'name' => $validated['name'],
                'slug' => $slug,
                'description' => $validated['description'],
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'image' => $imagePath,
                'category_id' => $validated['category_id'],
                'product_type' => $validated['product_type'],
                'is_active' => true,
            ]);

            $this->syncVariants($product, $request);
            $this->syncLegacyYarnColorsFromVariants($product);
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', [
            'product' => $product->load('variants'),
            'categories' => Category::all(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $this->validateProductRequest($request);
        $slug = $this->uniqueSlug($validated['name'], $product->id);

        DB::transaction(function () use ($request, $validated, $product, $slug) {
            $imagePath = $product->image;

            if ($request->hasFile('image')) {
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }

                $imagePath = $this->storeFloatingProductImage($request);
            }

            $product->update([
                'name' => $validated['name'],
                'slug' => $slug,
                'description' => $validated['description'],
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'category_id' => $validated['category_id'],
                'product_type' => $validated['product_type'],
                'image' => $imagePath,
                'is_active' => $request->boolean('is_active'),
            ]);

            $this->syncVariants($product, $request);
            $this->syncLegacyYarnColorsFromVariants($product);
        });

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    private function validateProductRequest(Request $request): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'product_type' => 'required|in:standard,custom',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:' . self::IMAGE_MAX_KB,
            'variants' => 'required|array|min:1',
            'variants.*.id' => 'nullable|integer|exists:product_variants,id',
            'variants.*.name' => 'required|string|max:255',
            'variants.*.yarn_color' => 'required|string|max:255',
            'variants.*.hex_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'variants.*.size' => 'nullable|string|max:255',
            'variants.*.material' => 'nullable|string|max:255',
            'variants.*.design_style' => 'nullable|string|max:255',
            'variants.*.set_quantity' => 'nullable|string|max:255',
            'variants.*.packaging_option' => 'nullable|string|max:255',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.status' => 'required|in:active,inactive',
            'variants.*.sku' => 'nullable|string|max:255',
            'variants.*.sort_order' => 'nullable|integer|min:0',
            'variants.*.image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:' . self::IMAGE_MAX_KB,
        ];

        $messages = [
            'image.image' => 'The product image must be an image file.',
            'image.mimes' => 'The product image must be a JPG, JPEG, PNG, or WEBP file.',
            'image.max' => 'The product image must be 5 MB or smaller.',
            'variants.required' => 'Add at least one product variant.',
            'variants.min' => 'Add at least one product variant.',
            'variants.*.image.image' => 'Each variant image must be an image file.',
            'variants.*.image.mimes' => 'Each variant image must be a JPG, JPEG, PNG, or WEBP file.',
            'variants.*.image.max' => 'Each variant image must be 5 MB or smaller.',
        ];

        foreach ((array) $request->input('variants', []) as $index => $variantData) {
            if (! is_array($variantData)) {
                continue;
            }

            $variantNumber = $index + 1;

            $messages['variants.' . $index . '.image.image'] = 'Variant ' . $variantNumber . ' image must be an image file.';
            $messages['variants.' . $index . '.image.mimes'] = 'Variant ' . $variantNumber . ' image must be a JPG, JPEG, PNG, or WEBP file.';
            $messages['variants.' . $index . '.image.max'] = 'Variant ' . $variantNumber . ' image must be 5 MB or smaller.';
        }

        return Validator::make($request->all(), $rules, $messages)->validate();
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (
            Product::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }

    private function syncVariants(Product $product, Request $request): void
    {
        $existingVariants = $product->variants()->get()->keyBy(fn ($variant) => (string) $variant->id);
        $submittedIds = [];

        foreach ((array) $request->input('variants', []) as $index => $variantData) {
            if (! is_array($variantData)) {
                continue;
            }

            $variantId = isset($variantData['id']) && $variantData['id'] !== '' ? (string) $variantData['id'] : null;
            $existingVariant = $variantId ? $existingVariants->get($variantId) : null;

            $imageInputName = 'variants.' . $index . '.image';
            $imagePath = $existingVariant?->image_path;

            if ($request->hasFile($imageInputName)) {
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }

                $imagePath = $request->file($imageInputName)->store('product-variants', 'public');
            }

            $payload = [
                'name' => $variantData['name'],
                'yarn_color' => $variantData['yarn_color'],
                'hex_color' => $variantData['hex_color'] ?? null,
                'size' => $variantData['size'] ?? null,
                'material' => $variantData['material'] ?? null,
                'design_style' => $variantData['design_style'] ?? null,
                'set_quantity' => $variantData['set_quantity'] ?? null,
                'packaging_option' => $variantData['packaging_option'] ?? null,
                'image_path' => $imagePath,
                'price' => $variantData['price'],
                'stock' => $variantData['stock'],
                'status' => $variantData['status'],
                'sort_order' => (int) ($variantData['sort_order'] ?? $index),
                'is_default' => $index === 0,
            ];

            $payload['sku'] = $this->resolveVariantSku($product, $variantData, $existingVariant);

            if ($existingVariant) {
                $existingVariant->update($payload);
                $submittedIds[] = $existingVariant->id;
            } else {
                $createdVariant = $product->variants()->create($payload);
                $submittedIds[] = $createdVariant->id;
            }
        }

        $product->variants()
            ->whereNotIn('id', $submittedIds)
            ->update([
                'status' => 'inactive',
                'stock' => 0,
                'is_default' => false,
            ]);

        $product->syncStockFromVariants();
    }

    private function syncLegacyYarnColorsFromVariants(Product $product): void
    {
        $variants = $product->variants()->get()->filter(fn ($variant) => filled($variant->yarn_color));

        $colorIds = $variants->map(function ($variant) {
            $color = YarnColor::firstOrCreate(
                ['slug' => Str::slug($variant->yarn_color)],
                [
                    'name' => $variant->yarn_color,
                    'hex_color' => $variant->hex_color,
                    'is_active' => true,
                    'sort_order' => 0,
                ]
            );

            if (! $color->hex_color && $variant->hex_color) {
                $color->update(['hex_color' => $variant->hex_color]);
            }

            return $color->id;
        })->unique()->values()->all();

        $product->yarnColors()->sync($colorIds);
    }

    private function resolveVariantSku(Product $product, array $variantData, ?\App\Models\ProductVariant $existingVariant = null): string
    {
        $submittedSku = trim((string) ($variantData['sku'] ?? ''));

        if ($submittedSku !== '') {
            $skuExists = \App\Models\ProductVariant::where('sku', $submittedSku)
                ->when($existingVariant, fn ($query) => $query->where('id', '!=', $existingVariant->id))
                ->exists();

            if (! $skuExists) {
                return $submittedSku;
            }

            if ($existingVariant && strcasecmp((string) $existingVariant->sku, $submittedSku) === 0) {
                return $existingVariant->sku;
            }

            return $this->generateUniqueVariantSku($product, $variantData, $existingVariant?->id);
        }

        if ($existingVariant && filled($existingVariant->sku)) {
            return $existingVariant->sku;
        }

        return $this->generateUniqueVariantSku($product, $variantData, $existingVariant?->id);
    }

    private function generateUniqueVariantSku(Product $product, array $variantData, ?int $ignoreVariantId = null): string
    {
        $baseParts = collect([
            $product->slug ?: $product->name,
            $variantData['name'] ?? $variantData['yarn_color'] ?? 'variant',
        ])
            ->filter()
            ->map(fn ($part) => Str::slug((string) $part, '-'))
            ->filter()
            ->all();

        $base = Str::upper(implode('-', $baseParts));

        if ($base === '') {
            $base = 'SKU';
        }

        $suffix = 1;

        do {
            $sku = $base . '-' . str_pad((string) $suffix, 3, '0', STR_PAD_LEFT);
            $exists = \App\Models\ProductVariant::where('sku', $sku)
                ->when($ignoreVariantId, fn ($query) => $query->where('id', '!=', $ignoreVariantId))
                ->exists();

            $suffix++;
        } while ($exists);

        return $sku;
    }

    private function storeFloatingProductImage(Request $request): string
    {
        $storedPath = $request->file('image')->store('products', 'public');
        $sourcePath = storage_path('app/public/' . $storedPath);
        $floatingPath = 'products/transparent/' . pathinfo($storedPath, PATHINFO_FILENAME) . '.png';
        $targetPath = storage_path('app/public/' . $floatingPath);

        if (ProductImage::createTransparentCopy($sourcePath, $targetPath)) {
            Storage::disk('public')->delete($storedPath);

            return $floatingPath;
        }

        return $storedPath;
    }

    private function buildExcelHtml(string $title, array $metadata, array $columns, array $rows, array $currencyColumnIndexes = []): string
    {
        $html = '<html><head><meta charset="UTF-8"><style>'
            . 'body{font-family:Calibri,Arial,sans-serif;background:#ffffff;color:#3f1d28;}'
            . '.title{font-size:20px;font-weight:700;color:#650c2a;padding:14px 10px;border-bottom:2px solid #f3c9d9;}'
            . '.meta{background:#fff6fa;color:#3f1d28;border:1px solid #f3c9d9;padding:6px 10px;}'
            . 'table{border-collapse:collapse;width:100%;margin-top:12px;}'
            . 'th{background:#650c2a;color:#ffffff;font-weight:700;text-align:center;border:1px solid #f3c9d9;padding:8px;}'
            . 'td{border:1px solid #f3c9d9;padding:7px;color:#3f1d28;vertical-align:top;}'
            . 'tr.alt td{background:#fff6fa;}'
            . '.num{text-align:right;}'
            . '</style></head><body>';

        $html .= '<table><tr><td class="title" colspan="' . count($columns) . '">' . $this->escapeExcel($title) . '</td></tr>';

        foreach ($metadata as $label => $value) {
            $html .= '<tr><td class="meta" colspan="2"><strong>' . $this->escapeExcel((string) $label) . ':</strong> ' . $this->escapeExcel((string) $value) . '</td>'
                . '<td class="meta" colspan="' . max(1, count($columns) - 2) . '"></td></tr>';
        }

        $html .= '<tr>';
        foreach ($columns as $column) {
            $html .= '<th>' . $this->escapeExcel($column) . '</th>';
        }
        $html .= '</tr>';

        foreach ($rows as $index => $row) {
            $html .= '<tr' . ($index % 2 === 1 ? ' class="alt"' : '') . '>';
            foreach ($row as $columnIndex => $value) {
                $isCurrency = in_array($columnIndex, $currencyColumnIndexes, true);
                $cellValue = $isCurrency ? 'PHP ' . number_format((float) $value, 2) : (string) $value;
                $html .= '<td' . ($isCurrency || is_numeric($value) ? ' class="num"' : '') . '>' . $this->escapeExcel($cellValue) . '</td>';
            }
            $html .= '</tr>';
        }

        $html .= '</table></body></html>';

        return $html;
    }

    private function escapeExcel(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
