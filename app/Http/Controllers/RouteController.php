<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\ContactanosMaileable;
use App\Models\Route;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class RouteController extends Controller
{
    public function index()
    {
        $rutas = Route::all();
        return response()->json($rutas);
    }

    public function index2()
    {
        $userId = Auth::id();

        $rutasCreadas = Route::where('user_id', $userId)->get();
        return response()->json($rutasCreadas);
    }

    public function create(Request $request)
    {
        $userId = Auth::id();

        $urls = [];

        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $image) {
                $path = $image->store('public/imagenes');
                $url = Storage::url($path);
                $urls[] = $url; // Añade la URL de la imagen al array
            }
        }

        // Convertir el array de URLs a JSON para almacenarla en la base de datos
        $imagesJson = json_encode($urls);

        // return response()->json($urls);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'distance' => 'required|numeric',
            'unevenness' => 'required|numeric',
            'difficulty' => 'required|string|max:50',
            'mapsIFrame' => 'required|string',
            'location' => 'required|string|max:255',
            // 'imagen' => 'required|string',
            // 'fecha' => 'required|date_format:d/m/Y',
            // 'hora' => 'required|date_format:H:i',
            'category' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Error en la validación de datos', 'errors' => $validator->errors()], 422);
        }

        try {
            // Convertir fecha y hora al formato adecuado para la base de datos
            $hora = Carbon::createFromFormat('H:i', $request->hora)->format('H:i:s');

            $route = Route::create([
                'title' => $request->title,
                'description' => $request->description,
                'distance' => $request->distance,
                'unevenness' => $request->unevenness,
                'difficulty' => $request->difficulty,
                'mapsIFrame' => $request->mapsIFrame,
                'location' => $request->location,
                'imagen' => $imagesJson,
                'fecha' => $request->fecha,
                'hora' => $hora,
                'category' => $request->category,
                'likes' => 0,
                'user_id' => $userId,
            ]);

            return response()->json(['message' => 'Ruta creada correctamente', 'route' => $route], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al crear la ruta', 'error' => $e->getMessage()], 500);
        }
    }

    public function inscribirse(Request $request)
    {
        $user = Auth::user();
        $rutaId = $request->ruta_id;

        try {
            $user->subscribedRoutes()->attach($rutaId);
            return response()->json(['message' => 'Te has inscrito a la ruta correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error inscribirte a la ruta: ' . $e->getMessage()], 500);
        }
    }

    public function darLike(Request $request)
    {
        $ruta = Route::find($request->ruta_id);

        $ruta->likes = $ruta->likes + 1;

        $ruta->save();
    }

    public function updatedLike($ruta_id)
    {
        try {
            $ruta = Route::find($ruta_id);
            return response()->json($ruta->likes, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener los Likes: ' . $e->getMessage()], 500);
        }
    }

    public function confirmar(Request $request)
    {
        $user = Auth::user();
        $nombre = $user->name;
        $email = $user->email;
        $fecha = $request->input('fecha');
        $hora = $request->input('hora');
        $nombreRuta = $request->input('nombreRuta');
        $mensaje = "Se ha inscrito a la ruta " . $nombreRuta . " la cual se va a realizar el día " . $fecha . " a las " . $hora;

        try {
            // Mail::to($email)->send(new ContactanosMaileable($nombre, $hora, $fecha, $nombreRuta, $mensaje));
            Mail::to($email)->send(new ContactanosMaileable($nombre, $hora, $fecha, $nombreRuta, $mensaje));
            $status = 'success';
            $statusMessage = 'Email sent successfully';
        } catch (\Exception $e) {
            $status = 'fail';
            $statusMessage = 'Failed to send email';
        }

        return response()->json([
            'nombre' => $nombre,
            'hora' => $hora,
            'fecha' => $fecha,
            'nombreRuta' => $nombreRuta,
            'mensaje' => $mensaje,
            'email' => $email,
            'status' => $status,
            'statusMessage' => $statusMessage,
        ]);
    }

    //Devuelve todas las rutas a la que se ha apuntado el usuario
    public function joinedRoutes()
    {
        $user = Auth::user();

        $rutas = $user->subscribedRoutes;

        return response()->json($rutas, 200);
    }

    //Devuelve el numero de participantes de una ruta
    public function numberParticipant($ruta_id)
    {
        $ruta = Route::find($ruta_id);
        $participantes = $ruta->subscribedUsers;
        return response()->json([$participantes->count()], 200);
    }

    public function getRoute($ruta_id)
    {
        $ruta = Route::find($ruta_id);
        return response()->json([$ruta], 200);
    }
}
