<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;

class LogoutController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            auth()->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Sesión cerrada correctamente.']);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
