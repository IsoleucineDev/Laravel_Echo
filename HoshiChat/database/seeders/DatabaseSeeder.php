<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuario de prueba
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Crear 4 usuarios más
        $users = User::factory(4)->create();

        // Agregar el usuario de prueba a la colección
        $users->push($testUser);

        // Crear conversaciones
        for ($i = 0; $i < 3; $i++) {
            $conversation = Conversation::factory()->create([
                'created_by' => $users->random()->id,
            ]);

            // Agregar 2-4 usuarios a cada conversación
            $conversationUsers = $users->random(rand(2, 4));
            $conversation->users()->attach($conversationUsers->pluck('id'));

            // Crear 5-10 mensajes por conversación
            Message::factory(rand(5, 10))->create([
                'conversation_id' => $conversation->id,
                'user_id' => $conversationUsers->random()->id,
            ]);
        }
    }
}
