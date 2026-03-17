<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
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
}
