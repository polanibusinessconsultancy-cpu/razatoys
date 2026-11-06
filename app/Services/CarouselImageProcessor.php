<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CarouselImageProcessor
{
    /**
     * WebP quality setting.
     */
    protected int $webpQuality = 85;

    /**
     * Process uploaded image: validate, optimize, and save to public banners storage.
     *
     * @param UploadedFile $file Uploaded image file
     * @param string $baseName Base name for image file (without extension)
     * @return string Stored relative path for database
     * @throws \Exception
     */
    public function process(UploadedFile $file, string $baseName): string
    {
        // Graceful fallback if GD is not loaded
        if (!extension_loaded('gd')) {
            $ext = $file->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('banners', $file, "{$baseName}.{$ext}");
            return "banners/{$baseName}.{$ext}";
        }

        try {
            // 1. Validate image
            $this->validateImage($file);

            // 2. Ensure banners directory exists on public disk
            if (!Storage::disk('public')->exists('banners')) {
                Storage::disk('public')->makeDirectory('banners');
            }

            // 3. Load image using GD
            $image = $this->loadImage($file->getRealPath());
            if (!$image) {
                throw new \Exception('Failed to load image');
            }

            $originalWidth = imagesx($image);
            $originalHeight = imagesy($image);

            // Preserve transparency
            imagealphablending($image, false);
            imagesavealpha($image, true);

            // 4. Resize if width is larger than 1200px
            $targetWidth = 1200;
            if ($originalWidth > $targetWidth) {
                $resized = $this->resizeImage($image, $targetWidth, $originalWidth, $originalHeight);
                $targetPath = Storage::disk('public')->path("banners/{$baseName}.webp");
                imagewebp($resized, $targetPath, $this->webpQuality);
                imagedestroy($resized);
            } else {
                $targetPath = Storage::disk('public')->path("banners/{$baseName}.webp");
                imagewebp($image, $targetPath, $this->webpQuality);
            }

            imagedestroy($image);
            return "banners/{$baseName}.webp";
        } catch (\Exception $e) {
            $this->deleteImages($baseName);
            throw $e;
        }
    }

    /**
     * Validate uploaded image file.
     */
    protected function validateImage(UploadedFile $file): void
    {
        $realPath = $file->getRealPath();
        $dimensions = getimagesize($realPath);
        if (!$dimensions) {
            throw new \Exception('Invalid image file or file format not supported');
        }

        $width = $dimensions[0];
        $height = $dimensions[1];

        // Decoded pixel size: 4 bytes per pixel (RGBA) + overhead factor (1.5)
        $estimatedMemory = $width * $height * 4 * 1.5;

        // Parse php memory limit
        $memoryLimitStr = ini_get('memory_limit');
        if ($memoryLimitStr && $memoryLimitStr !== '-1') {
            $limit = $this->parseMemoryLimit($memoryLimitStr);
            $currentUsage = memory_get_usage(true);

            if (($currentUsage + $estimatedMemory) > ($limit * 0.9)) {
                $formattedLimit = round($limit / (1024 * 1024)) . 'MB';
                throw new \Exception("The image resolution ({$width}x{$height}) is too large to process safely within the server's memory limit ({$formattedLimit}). Please resize the image before uploading.");
            }
        }
    }

    /**
     * Helper to parse memory limit string (e.g. 512M, 1G) to bytes.
     */
    protected function parseMemoryLimit(string $val): int
    {
        $val = trim($val);
        if (empty($val)) {
            return 128 * 1024 * 1024; // Default fallback 128MB
        }
        $last = strtolower($val[strlen($val) - 1]);
        $val = (int)$val;
        switch ($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return $val;
    }

    /**
     * Load image from file path.
     */
    protected function loadImage(string $path)
    {
        $mimeType = mime_content_type($path);

        return match ($mimeType) {
            'image/jpeg' => imagecreatefromjpeg($path),
            'image/png' => imagecreatefrompng($path),
            'image/webp' => imagecreatefromwebp($path),
            default => false,
        };
    }

    /**
     * Resize image maintaining aspect ratio.
     */
    protected function resizeImage($sourceImage, int $targetWidth, int $originalWidth, int $originalHeight)
    {
        $ratio = $originalHeight / $originalWidth;
        $targetHeight = (int) ($targetWidth * $ratio);

        $resized = imagecreatetruecolor($targetWidth, $targetHeight);

        imagealphablending($resized, false);
        imagesavealpha($resized, true);

        imagecopyresampled(
            $resized,
            $sourceImage,
            0,
            0,
            0,
            0,
            $targetWidth,
            $targetHeight,
            $originalWidth,
            $originalHeight
        );

        return $resized;
    }

    /**
     * Delete image files. Never delete static theme or shared asset images.
     */
    public function deleteImages(string $imageName): bool
    {
        if (empty($imageName)) {
            return false;
        }

        // NEVER delete static theme assets or main assets img folder files
        if (str_contains($imageName, 'one-stop-mart/') || str_contains($imageName, 'assets/img/')) {
            return false;
        }

        $disk = Storage::disk('public');
        $deleted = false;

        // If the imageName contains the banners/ prefix already
        $relativeName = str_starts_with($imageName, 'banners/') ? substr($imageName, 8) : $imageName;

        // Remove extension from base search if present
        $baseName = pathinfo($relativeName, PATHINFO_FILENAME);

        // Try deleting different format suffixes
        $formats = [
            "banners/{$relativeName}",
            "banners/{$baseName}.webp",
            "banners/{$baseName}.png",
            "banners/{$baseName}.jpg",
            "banners/{$baseName}.jpeg",
        ];

        foreach ($formats as $path) {
            if ($disk->exists($path)) {
                if ($disk->delete($path)) {
                    $deleted = true;
                }
            }
        }

        return $deleted;
    }
}
