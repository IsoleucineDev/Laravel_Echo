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
        $firstUser = $users->first();
        
        echo "\nCreando conversación 1 con created_by: " . $firstUser->id;
        
        $conversation1 = Conversation::create([
            'name' => 'General Chat',
            'description' => 'General conversation for everyone',
            'created_by' => $firstUser->id,
        ]);

        $conversation1->users()->attach($users->pluck('id')->toArray());

        $secondUser = $users->skip(1)->first();
        
        echo "\nCreando conversación 2 con created_by: " . $secondUser->id;
        
        $conversation2 = Conversation::create([
            'name' => 'Project Discussion',
            'description' => 'Discussion about the HoshiChat project',
            'created_by' => $secondUser->id,
        ]);

        $conversation2->users()->attach(
            $users->take(3)->pluck('id')->toArray()
        );

        $thirdUser = $users->skip(2)->first();
        
        echo "\nCreando conversación 3 con created_by: " . $thirdUser->id;
        
        $conversation3 = Conversation::create([
            'name' => 'Random Topics',
            'description' => 'Talk about anything!',
            'created_by' => $thirdUser->id,
        ]);

        $conversation3->users()->attach(
            $users->slice(1, 4)->pluck('id')->toArray()
        );
        
        echo "\nConversaciones creadas exitosamente!\n";
    }
}
