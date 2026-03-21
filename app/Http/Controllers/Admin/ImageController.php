<?php

namespace App\Http\Controllers\Admin;

use App\Models\mongoDB\Image;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ImageRequest;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function __construct(private Image $image, private ImageService $imageService) {}

    public function store(ImageRequest $request)
    {
        try {
            $data = $request->all();
            \Debugbar::info($data);
            $filename = $this->imageService->uploadImage($request->file('image'));

            $this->image->create([
                'filename' => $filename,
                'entity_type' => $data['entity_type'],
                'entity_id' => $data['entity_id'],
            ]);
            $images = $this->image->where('entity_type', $data['entity_type'])
                                  ->where('entity_id', $data['entity_id'])
                                  ->get();
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

    public function index(Request $request)
    {
        try {
            $query = $this->image->newQuery();
            
            if ($request->filled('entity_type')) {
                $query->where('entity_type', $request->input('entity_type'));
            }
            if ($request->filled('entity_id')) {
                $query->where('entity_id', $request->input('entity_id'));
            }

            $images = $query->get();
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
            $image = $this->image->where('filename', $filename)->first();
            if ($image) {
                $entityType = $image->entity_type;
                $entityId = $image->entity_id;
                
                $this->imageService->deleteImage($filename);
                $image->delete();

                $images = $this->image->where('entity_type', $entityType)
                                      ->where('entity_id', $entityId)
                                      ->get();
            } else {
                $images = collect();
            }
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

    public function modify(Request $request, $id)
    {
        try {
            \Debugbar::info($request);
            $data = $request->all();
            \Debugbar::info($data);
            
            $image = $this->image->where('_id', $id)->first();
            if ($image) {
                $image->update([
                    'is_active' => false,
                    'alt' => $data['alt'],
                    'caption' => $data['caption']
                ]);
                $images = $this->image->where('entity_type', $image->entity_type)
                                      ->where('entity_id', $image->entity_id)
                                      ->get();
            } else {
                $images = collect();
            }
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
