<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    /**
     * Compress and store image with size limit
     * 
     * @param UploadedFile $file
     * @param string $directory
     * @param int $maxSizeKB Maximum file size in KB (default 2048 = 2MB)
     * @param int $quality Initial JPEG quality (1-100)
     * @return string Path to stored image
     */
    public function compressAndStore(
        UploadedFile $file, 
        string $directory, 
        int $maxSizeKB = 2048,
        int $quality = 85
    ): string {
        // If file is already under limit, store as is
        if ($file->getSize() <= $maxSizeKB * 1024) {
            return $file->store($directory, 'public');
        }

        // Get image info
        $imageInfo = getimagesize($file->getRealPath());
        if (!$imageInfo) {
            throw new \Exception('Invalid image file');
        }

        $mimeType = $imageInfo['mime'];
        $width = $imageInfo[0];
        $height = $imageInfo[1];

        // Create image resource based on type
        $source = $this->createImageFromFile($file->getRealPath(), $mimeType);
        if (!$source) {
            throw new \Exception('Failed to create image resource');
        }

        // Calculate new dimensions if needed
        $maxDimension = 1920; // Max width/height
        if ($width > $maxDimension || $height > $maxDimension) {
            $ratio = min($maxDimension / $width, $maxDimension / $height);
            $newWidth = (int)($width * $ratio);
            $newHeight = (int)($height * $ratio);
        } else {
            $newWidth = $width;
            $newHeight = $height;
        }

        // Create new image resource
        $destination = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency for PNG and GIF
        if (in_array($mimeType, ['image/png', 'image/gif'])) {
            imagealphablending($destination, false);
            imagesaveasalpha($destination, true);
            $transparent = imagecolorallocatealpha($destination, 255, 255, 255, 127);
            imagefilledrectangle($destination, 0, 0, $newWidth, $newHeight, $transparent);
        }

        // Resize
        imagecopyresampled($destination, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($source);

        // Generate filename
        $filename = uniqid() . '_' . time() . '.jpg'; // Convert to JPEG for better compression
        $path = $directory . '/' . $filename;

        // Compress and save with decreasing quality until under size limit
        $currentQuality = $quality;
        $tempPath = sys_get_temp_dir() . '/' . $filename;
        
        while ($currentQuality >= 10) {
            imagejpeg($destination, $tempPath, $currentQuality);
            $fileSize = filesize($tempPath);
            
            if ($fileSize <= $maxSizeKB * 1024) {
                break;
            }
            
            $currentQuality -= 5;
        }

        imagedestroy($destination);

        // If still too large, further reduce dimensions
        if (filesize($tempPath) > $maxSizeKB * 1024) {
            $source = imagecreatefromjpeg($tempPath);
            $width = imagesx($source);
            $height = imagesy($source);
            
            $newWidth = (int)($width * 0.8);
            $newHeight = (int)($height * 0.8);
            
            $destination = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($destination, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($source);
            
            imagejpeg($destination, $tempPath, 60);
            imagedestroy($destination);
        }

        // Store to public disk
        $content = file_get_contents($tempPath);
        Storage::disk('public')->put($path, $content);
        unlink($tempPath);

        return $path;
    }

    /**
     * Create image resource from file based on MIME type
     */
    private function createImageFromFile(string $path, string $mimeType)
    {
        return match ($mimeType) {
            'image/jpeg', 'image/jpg' => imagecreatefromjpeg($path),
            'image/png' => imagecreatefrompng($path),
            'image/gif' => imagecreatefromgif($path),
            'image/webp' => imagecreatefromwebp($path),
            default => null,
        };
    }
}
