<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDateRequest;
use App\Models\Date;
use Illuminate\Support\Facades\Auth;

class DateController extends Controller
{
    /**
     * Get all dates for a specific user.
     */
    public function getDatesByUser($userId)
    {
        $dates = Date::where('user_id', $userId)
            ->select('id', 'asunto', 'fecha', 'hora', 'user_id', 'created_at', 'updated_at')
            ->get();
        
        if ($dates->isEmpty()) {
            return response()->json(['message' => 'No hay citas para este usuario.', 'dates' => []], 200);
        }
        
        return response()->json(['message' => 'Citas obtenidas exitosamente.', 'dates' => $dates], 200);
    }

    /**
     * Delete a date by ID.
     */
    public function destroy($dateId)
    {
        $date = Date::find($dateId);

        if (!$date) {
            return response()->json(['message' => 'Cita no encontrada.'], 404);
        }

        $date->delete();

        return response()->json(['message' => 'Cita eliminada exitosamente.'], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDateRequest $request)
    {
        // La validación ahora ocurre automáticamente gracias a StoreDateRequest.
        // Si falla, Laravel devolverá una respuesta 422 con los errores.
        $validatedData = $request->validated();
        
        // Asegúrate de que user_id sea un entero
        $validatedData['user_id'] = (int) $validatedData['user_id'];
        
        $date = Date::create($validatedData);
        return response()->json(['message' => 'Cita registrada exitosamente.', 'date' => $date], 201);
    }
}