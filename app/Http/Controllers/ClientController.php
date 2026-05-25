<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Repositories\ClientRepository;
use App\Http\Requests\Client\StoreClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use Illuminate\Http\JsonResponse;

class ClientController extends Controller
{
    public function __construct(
        private ClientRepository $clientRepository
    ) {}

    public function index(): JsonResponse
    {
        $filters = request()->only(['search', 'status']);
        $clients = $this->clientRepository->getAll($filters);

        return response()->json($clients);
    }

    public function store(StoreClientRequest $request): JsonResponse
    {
        $client = $this->clientRepository->create([
            'name' => $request->name,
            'status' => $request->status ?? 'prospecto',
            'description' => $request->description,
            'user_id' => auth()->id(),
        ]);

        return response()->json($client, 201);
    }

    public function show(int $id): JsonResponse
    {
        $client = $this->clientRepository->findById($id);

        return response()->json($client);
    }

    public function update(UpdateClientRequest $request, Client $client): JsonResponse
    {
        $client = $this->clientRepository->update($client, $request->validated());

        return response()->json($client);
    }

    public function destroy(Client $client): JsonResponse
    {
        $this->clientRepository->delete($client);

        return response()->json(['message' => 'Cliente eliminado correctamente.']);
    }
}
