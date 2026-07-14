<?php

namespace Database\Seeders;

use App\Models\ChatbotKnowledgeSource;
use App\Models\User;
use Illuminate\Database\Seeder;

class ChatbotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $users = User::factory(10)->hasChatbots(3)->create();
        foreach ($users as $user) {
            $user->markEmailAsVerified();
            $user->assignRole('Cliente');
            $user->chatbots->each(function ($chatbot) {
                $chatbot->chatbotKnowledgeSources()->createMany(
                    ChatbotKnowledgeSource::factory(1)->make()->toArray()
                );
            });
        }
    }
}
