<?php

namespace App\Http\Controllers\Admin;

use App\Models\mongoDB\Image;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ImageRequest;

class ImageController extends Controller
{
    public function __construct(private Image $image, private ImageService $imageService) {}

    public function store(ImageRequest $request)
    {
        try {
            $data = $request->validated();

            $filename = $this->imageService->uploadImage($request->file('image'));

            $this->image->create([
                'filename' => $filename
            ]);
            $images = $this->image->all();
            return response()->json([
                'imageGallery' => view('components.image-gallery', ['images' => $images])->render(),
            ], 200);
        } catch (\Exception $e) {
            \Debugbar::info($e->getMessage());
            return response()->json([
                'message' => \Lang::get('admin/notification.error'),
            ], 500);
        }
    }

    public function index()
    {
        try {
            $images = $this->image->all();
            \Debugbar::info($images);
            return response()->json([
                'imageGallery' => view('components.image-gallery', ['images' => $images])->render(),
            ], 200);
        } catch (\Exception $e) {
            \Debugbar::info($e->getMessage());
            return response()->json([
                'message' => \Lang::get('admin/notification.error'),
            ], 500);
        }
    }

    public function showThumb($filename)
    {
        try {
            $disk = Storage::disk('public');
            $path = "images/gallery/thumbnail/{$filename}";

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

    public function destroy($filename)
    {
        try {
            $this->imageService->deleteImage($filename);
            $this->image->where('filename', $filename)->delete();
            $images = $this->image->all();
            return response()->json([
                'imageGallery' => view('components.image-gallery', ['images' => $images])->render(),
            ], 200);
        } catch (\Exception $e) {
            \Debugbar::info($e->getMessage());
            return response()->json([
                'message' => \Lang::get('admin/notification.error'),
            ], 500);
        }
    }

    public function modify($id)
    {
        try {
            \Debugbar::info($id);
            $this->image->where('_id', $id)->update([
                'is_active' => false
            ]);
            $images = $this->image->all();
            return response()->json([
                'imageGallery' => view('components.image-gallery', ['images' => $images])->render(),
            ], 200);
        } catch (\Exception $e) {
            \Debugbar::info($e->getMessage());
            return response()->json([
                'message' => \Lang::get('admin/notification.error'),
            ], 500);
        }
    }
}
