<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConversationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->count() < 2) {
            return;
        }

        // Crear conversaciones de prueba
        $conversation1 = Conversation::create([
            'name' => 'General Chat',
            'description' => 'General conversation for everyone',
        ]);

        $conversation1->users()->attach($users->pluck('id')->toArray());

        $conversation2 = Conversation::create([
            'name' => 'Project Discussion',
            'description' => 'Discussion about the HoshiChat project',
        ]);

        $conversation2->users()->attach(
            $users->take(3)->pluck('id')->toArray()
        );

        $conversation3 = Conversation::create([
            'name' => 'Random Topics',
            'description' => 'Talk about anything!',
        ]);

        $conversation3->users()->attach(
            $users->slice(1, 4)->pluck('id')->toArray()
        );
    }
}
