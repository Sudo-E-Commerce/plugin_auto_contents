<?php 

return [
    'menu' => [
        'plugin_auto_content' => [
            'type'              => 'multiple',
            'name'              => 'Auto content',
            'icon'              => 'fab fa-autoprefixer',
            'childs' => [
                [
                    'name'      => 'Thêm mới từ khóa',
                    'route'     => 'admin.ac_keywords.create',
                    'role'      => 'ac_keywords_create'
                ],
                [
                    'name'      => 'Danh sách từ khóa',
                    'route'     => 'admin.ac_keywords.index',
                    'role'      => 'ac_keywords_index',
                    'active'    => ['admin.ac_keywords.edit' ]
                ],
                [
                    'name'      => 'Cấu hình',
                    'route'     => 'admin.settings.general_ai',
                    'role'      => 'settings_general_ai'
                ],
            ]
        ],
    ]
];
