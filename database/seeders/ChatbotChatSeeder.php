<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class ChatbotChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $users = User::all();
        foreach ($users as $user) {
            $user->chatbotChats()->create([
                'chatbot_id' => 'a08a555a-1e39-45fe-bacd-474c89f69edf',
                'name' => 'Chat de '.$user->name,
            ]);
        }
    }
}
