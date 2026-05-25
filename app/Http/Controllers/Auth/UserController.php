<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            return response()->json(auth()->user());
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
