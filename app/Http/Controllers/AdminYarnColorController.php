<?php

namespace App\Http\Controllers;

use App\Models\YarnColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class AdminYarnColorController extends Controller
{
    public function store(Request $request)
    {
        $validated = $this->validateColor($request);

        $previewPath = $request->hasFile('preview_image')
            ? $request->file('preview_image')->store('yarn-colors', 'public')
            : null;

        YarnColor::create([
            ...$validated,
            'slug' => Str::slug($validated['name']),
            'preview_image_path' => $previewPath,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        YarnColor::resetActiveCache();

        return back()->with('success', 'Yarn color added.');
    }

    public function update(Request $request, YarnColor $yarnColor)
    {
        $validated = $this->validateColor($request, $yarnColor);
        $previewPath = $yarnColor->preview_image_path;

        if ($request->hasFile('preview_image')) {
            if ($previewPath) {
                Storage::disk('public')->delete($previewPath);
            }

            $previewPath = $request->file('preview_image')->store('yarn-colors', 'public');
        }

        $yarnColor->update([
            ...$validated,
            'slug' => Str::slug($validated['name']),
            'preview_image_path' => $previewPath,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        YarnColor::resetActiveCache();

        return back()->with('success', 'Yarn color updated.');
    }

    public function destroy(YarnColor $yarnColor)
    {
        if ($yarnColor->preview_image_path) {
            Storage::disk('public')->delete($yarnColor->preview_image_path);
        }

        $yarnColor->delete();
        YarnColor::resetActiveCache();

        return back()->with('success', 'Yarn color deleted.');
    }

    private function validateColor(Request $request, ?YarnColor $yarnColor = null): array
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'hex_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'sort_order' => 'nullable|integer|min:0|max:999',
            'preview_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $slug = Str::slug($validated['name']);
        $slugExists = YarnColor::where('slug', $slug)
            ->when($yarnColor, fn ($query) => $query->whereKeyNot($yarnColor->id))
            ->exists();

        if ($slugExists) {
            throw ValidationException::withMessages([
                'name' => 'A yarn color with this name already exists.',
            ]);
        }

        return $validated;
    }
}
