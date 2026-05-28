<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StoreClientRequest;
use App\Repositories\Contracts\Clients\ClientRepositoryInterface;
use Illuminate\Http\JsonResponse;

class StoreController extends Controller
{
    protected ClientRepositoryInterface $clientRepository;

    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function __invoke(StoreClientRequest $request): JsonResponse
    {
        try {
            $client = $this->clientRepository->store(
                $request->input('name'),
                $request->input('status', 'prospecto'),
                $request->input('description'),
                auth()->id()
            );

            return response()->json($client, 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
