<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductImage
{
    public static function url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        $image = trim($path);

        if (Str::startsWith($image, ['http://', 'https://', '//'])) {
            return $image;
        }

        $relativePath = Str::startsWith($image, 'storage/')
            ? Str::after($image, 'storage/')
            : $image;

        if (Storage::disk('public')->exists($relativePath)) {
            return Storage::url($relativePath);
        }

        if (Str::startsWith($image, 'images/')) {
            return asset($image);
        }

        if (file_exists(public_path($image))) {
            return asset($image);
        }

        return null;
    }

    public static function floatingPath(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        $image = trim($path);

        if (Str::startsWith($image, ['http://', 'https://', '//'])) {
            return $image;
        }

        if (Str::startsWith($image, 'images/products/') && ! Str::startsWith($image, 'images/products/transparent/')) {
            $floatingPath = 'images/products/transparent/' . basename($image);

            if (file_exists(public_path($floatingPath))) {
                return $floatingPath;
            }
        }

        if (Str::startsWith($image, 'products/') && ! Str::startsWith($image, 'products/transparent/')) {
            $floatingPath = 'products/transparent/' . pathinfo($image, PATHINFO_FILENAME) . '.png';

            if (Storage::disk('public')->exists($floatingPath)) {
                return $floatingPath;
            }
        }

        return $image;
    }

    public static function floatingUrl(?string $path): ?string
    {
        return self::url(self::floatingPath($path));
    }

    public static function createTransparentCopy(string $sourcePath, string $targetPath): bool
    {
        $info = @getimagesize($sourcePath);

        if (! $info) {
            return false;
        }

        $image = match ($info[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($sourcePath),
            IMAGETYPE_PNG => @imagecreatefrompng($sourcePath),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($sourcePath) : false,
            default => false,
        };

        if (! $image) {
            return false;
        }

        imagepalettetotruecolor($image);
        imagealphablending($image, false);
        imagesavealpha($image, true);

        $width = imagesx($image);
        $height = imagesy($image);
        $visited = str_repeat("\0", $width * $height);
        $queue = new \SplQueue();

        $enqueue = function (int $x, int $y) use (&$visited, $queue, $image, $width): void {
            $index = $y * $width + $x;

            if ($visited[$index] !== "\0" || ! self::isEdgeBackgroundPixel(imagecolorat($image, $x, $y))) {
                return;
            }

            $visited[$index] = "\1";
            $queue->enqueue([$x, $y]);
        };

        for ($x = 0; $x < $width; $x++) {
            $enqueue($x, 0);
            $enqueue($x, $height - 1);
        }

        for ($y = 0; $y < $height; $y++) {
            $enqueue(0, $y);
            $enqueue($width - 1, $y);
        }

        $transparent = imagecolorallocatealpha($image, 255, 255, 255, 127);
        $neighbors = [
            [-1, -1], [0, -1], [1, -1],
            [-1, 0],           [1, 0],
            [-1, 1],  [0, 1],  [1, 1],
        ];

        while (! $queue->isEmpty()) {
            [$x, $y] = $queue->dequeue();

            imagesetpixel($image, $x, $y, $transparent);

            foreach ($neighbors as [$dx, $dy]) {
                $nextX = $x + $dx;
                $nextY = $y + $dy;

                if ($nextX < 0 || $nextY < 0 || $nextX >= $width || $nextY >= $height) {
                    continue;
                }

                $enqueue($nextX, $nextY);
            }
        }

        $directory = dirname($targetPath);

        if (! is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $saved = imagepng($image, $targetPath, 6);
        imagedestroy($image);

        return $saved;
    }

    private static function isEdgeBackgroundPixel(int $rgba): bool
    {
        $alpha = ($rgba & 0x7F000000) >> 24;
        $red = ($rgba >> 16) & 255;
        $green = ($rgba >> 8) & 255;
        $blue = $rgba & 255;
        $max = max($red, $green, $blue);
        $min = min($red, $green, $blue);

        return $alpha >= 120 || ($min >= 238 && ($max - $min) <= 28);
    }
}
