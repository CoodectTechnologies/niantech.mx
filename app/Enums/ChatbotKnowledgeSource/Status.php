<?php

namespace App\Enums\ChatbotKnowledgeSource;

enum Status: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
}
