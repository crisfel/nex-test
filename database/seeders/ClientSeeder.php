<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Contact;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin Demo',
            'email' => 'admin@demo.com',
            'password' => bcrypt('password'),
        ]);

        $clients = [
            [
                'name' => 'TechCorp Solutions',
                'status' => 'activo',
                'description' => 'Empresa de soluciones tecnológicas.',
                'contacts' => [
                    ['name' => 'Carlos Lopez', 'email' => 'carlos@techcorp.com', 'phone' => '+52 5512345678', 'is_primary' => true],
                    ['name' => 'Maria Garcia', 'email' => 'maria@techcorp.com', 'phone' => '+52 5512345679', 'is_primary' => false],
                ],
            ],
            [
                'name' => 'GreenField Agriculture',
                'status' => 'activo',
                'description' => 'Productora de insumos agrícolas.',
                'contacts' => [
                    ['name' => 'Pedro Sanchez', 'email' => 'pedro@greenfield.com', 'phone' => null, 'is_primary' => true],
                ],
            ],
            [
                'name' => 'BlueOcean Ventures',
                'status' => 'prospecto',
                'description' => 'Fondo de inversión en etapa de evaluación.',
                'contacts' => [
                    ['name' => 'Ana Torres', 'email' => 'ana@blueocean.com', 'phone' => '+1 5550192837', 'is_primary' => true],
                    ['name' => 'Luis Rivera', 'email' => 'luis@blueocean.com', 'phone' => null, 'is_primary' => false],
                ],
            ],
            [
                'name' => 'RedSky Logistics',
                'status' => 'inactivo',
                'description' => null,
                'contacts' => [
                    ['name' => 'Sofia Medina', 'email' => 'sofia@redsky.com', 'phone' => '+34 912345678', 'is_primary' => true],
                ],
            ],
            [
                'name' => 'YellowStone Consulting',
                'status' => 'prospecto',
                'description' => null,
                'contacts' => [],
            ],
        ];

        foreach ($clients as $clientData) {
            $contacts = $clientData['contacts'] ?? [];
            unset($clientData['contacts']);

            $client = Client::factory()->create(array_merge($clientData, [
                'user_id' => $user->id,
            ]));

            foreach ($contacts as $contactData) {
                $contactData['user_id'] = $user->id;
                $client->contacts()->create($contactData);
            }
        }
    }
}
