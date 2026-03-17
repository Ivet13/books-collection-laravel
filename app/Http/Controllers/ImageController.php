<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\mongoDB\Image;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:2048'
        ]);

        if ($request->file('file')->isValid()) {
            $ruta = $request->file('file')->store('uploads', 'public');

            return response()->json([
                'mensaje' => 'Archivo subido correctamente',
                'ruta' => $ruta
            ]);
        }

        return response()->json([
            'mensaje' => 'Error al subir archivo'
        ], 400);
    }

    public function delete($id)
    {
        $image = Image::findOrFail($id);

        // Borrar archivo físico
        Storage::disk('public')->delete($image->ruta);

        // Borrar registro en BD
        $image->delete();

        return response()->json([
            'mensaje' => 'Archivo eliminado correctamente'
        ]);
    }
}
