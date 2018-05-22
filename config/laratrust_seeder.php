<?php

return [
    'role_structure' => [
        'superadministrator' => [
            'admins' => 'c,r,u,d',
            'acl' => 'c,r,u,d',
            'profile' => 'r,u'
        ],
        'administrator' => [
            'admins' => 'c,r,u,d',
            'profile' => 'r,u'
        ]
    ],
    'permission_structure' => [

    ],
    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
