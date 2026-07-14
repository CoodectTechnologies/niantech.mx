<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as ModelsPermission;
use Spatie\Translatable\HasTranslations;

class Permission extends ModelsPermission
{
    use HasTranslations;

    public $translatable = ['alias'];

    public static function getByGroups() {
        return Permission::orderBy('id', 'desc')->get()->groupBy(function ($permission) {
            return explode('-', $permission->name)[0];
        });
    }
}
