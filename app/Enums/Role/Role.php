<?php

namespace App\Enums\Role;

enum Role: string
{
    case ADMINISTRATOR = 'Administrador';
    case CLIENT = 'Cliente';
    case COPYWRITER = 'Copywriter';
    case ECOMMERCE = 'E-commerce';
    case WEB = 'Web';
}
