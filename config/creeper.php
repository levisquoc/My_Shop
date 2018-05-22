<?php
return [

    //Site Setting config
    'site_setting' => [

        'type' => ['text', 'text-area', 'text-editor', 'image', 'number', 'code-editor'],

        'group' => ['site', 'admin']

    ],
    /*
    |--------------------------------------------------------------------------
    | Page Target
    |--------------------------------------------------------------------------
    |
    | This option controls page target to get RSS
    */
    'page_target' => [
        '24h',
        'afamily.vn',
        'VNEXPRESS',
        'dantri'
    ],

    'position_advert' => [
        'Banner',
        'Sidebar',
        'TopContent',
        'BottomContent'
    ],

    // Language
    'language_default' => 'vi',

    'language' => [
        0 => [
            'name' => 'Tiếng Việt',
            'code' => 'vi',
            'flag' => '/language/icon/vietnam.png'
        ],
        1 => [
            'name' => 'English',
            'code' => 'en',
            'flag' => '/language/icon/united-kingdom.png'
        ],
//            2 => [
//                'name' => 'China',
//                'code' => 'cn',
//                'flag' => '/language/icon/china.png'
//            ],
//            3 => [
//                'name' => 'Japanese',
//                'code' => 'jp',
//                'flag' => '/language/icon/japan.png'
//            ]
    ]
]
?>