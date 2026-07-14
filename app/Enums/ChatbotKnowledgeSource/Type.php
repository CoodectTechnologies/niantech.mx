<?php

namespace App\Enums\ChatbotKnowledgeSource;

enum Type: string
{
    case FILE = 'file';
    case URL = 'url';
}
