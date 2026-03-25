<?php

namespace App\Http\Controllers\Public;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function __construct() {}

    public function showImage($entity, $entityId, $filename)
    {
        try {
            $disk = Storage::disk('public');
            $path = "images/{$entity}/{$entityId}/{$filename}";

            if ($disk->exists($path)) {
                return response($disk->get($path), 200)->header('Content-Type', 'image/webp');
            } else {
                return response()->json([
                    'message' => \Lang::get('admin/notification.error'),
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => \Lang::get('admin/notification.error'),
            ], 500);
        }
    }
}
