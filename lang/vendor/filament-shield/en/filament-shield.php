<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Table Columns
    |--------------------------------------------------------------------------
    */

    'column.name' => 'Name',
    'column.guard_name' => 'Guard Name',
    'column.roles' => 'Roles',
    'column.permissions' => 'Permissions',
    'column.updated_at' => 'Updated At',

    /*
    |--------------------------------------------------------------------------
    | Form Fields
    |--------------------------------------------------------------------------
    */

    'field.name' => 'Name',
    'field.guard_name' => 'Guard Name',
    'field.permissions' => 'Permissions',
    'field.select_all.name' => 'Select All',
    'field.select_all.message' => 'Enable all Permissions currently <span class="text-primary font-medium">Enabled</span> for this role',

    /*
    |--------------------------------------------------------------------------
    | Navigation & Resource
    |--------------------------------------------------------------------------
    */

    'nav.group' => 'Gerenciamento de Permissões',
    'nav.role.label' => 'Permissões',
    'nav.role.icon' => 'heroicon-o-shield-check',
    'resource.label.role' => 'Permissão',
    'resource.label.roles' => 'Permissões',

    /*
    |--------------------------------------------------------------------------
    | Section & Tabs
    |--------------------------------------------------------------------------
    */

    'section' => 'Entities',
    'resources' => 'Resources',
    'widgets' => 'Widgets',
    'pages' => 'Pages',
    'custom' => 'Custom Permissions',

    /*
    |--------------------------------------------------------------------------
    | Messages
    |--------------------------------------------------------------------------
    */

    'forbidden' => 'You do not have permission to access',

    /*
    |--------------------------------------------------------------------------
    | Resource Permissions' Labels
    |--------------------------------------------------------------------------
    */

    'resource_permission_prefixes_labels' => [
        'view' => 'Ver',
        'view_any' => 'Ver Qualquer',
        'create' => 'Criar',
        'update' => 'Atualizar',
        'delete' => 'Deletar',
        'delete_any' => 'Deletar Qualquer',
        'force_delete' => 'Deletar Permanentemente',
        'force_delete_any' => 'Deletar Permanentemente Qualquer',
        'restore' => 'Restaurar',
        'reorder' => 'Reordenar',
        'restore_any' => 'Restaurar Qualquer',
        'replicate' => 'Replicar',
        'export' => 'Exportar',
    ],
];
