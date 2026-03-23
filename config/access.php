<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Access Modules & Actions
    |--------------------------------------------------------------------------
    | Permission name format: "{action} {module}.{controller}"
    | Example: "create programs.program", "upload programs.material"
    |
    | To add a new module: add it here, then call
    |   PermissionService::initPermissions(true)
    | SuperAdmin gets all new permissions automatically.
    */

    'access_modules' => [
        'programs' => ['program', 'material'],
        'learners' => ['enrollment', 'progress'],
        'users'    => ['user', 'role'],
    ],

    'access_actions' => [
        'list', 'read', 'create',
        'update', 'delete', 'upload', 'approve',
    ],

    'extra_permissions' => [
        'view all programs',
    ],

];
