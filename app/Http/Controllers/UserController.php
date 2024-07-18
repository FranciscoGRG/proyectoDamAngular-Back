<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function updateImage(Request $request)
    {
        $user = Auth::user();

        // Borra la imagen anterior si existe
        if ($user->profile_image) {
            Storage::delete('public/profile_images/' . $user->profile_image);
        }

        // Decodifica la imagen base64 y guarda el archivo
        $imageData = $request->input('profile_image');
        $filename = time() . '.png';
        $imagePath = 'public/profile_images/' . $filename;

        Storage::put($imagePath, base64_decode($imageData));

        // Actualiza el campo profile_image del usuario
        $user->profile_image = $filename;
        $user->save();

        return response()->json(['profileImage' => $user->profile_image]);
    }
}
