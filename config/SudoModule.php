<?php 

return [
    'modules' => [
        'ac_keywords' => [
            'name'          => 'Auto content từ khóa',
            'permision'     => [
                [ 'type' => 'index', 'name' => 'Truy cập' ],
                [ 'type' => 'create', 'name' => 'Thêm' ],
                [ 'type' => 'edit', 'name' => 'Sửa' ],
                [ 'type' => 'restore', 'name' => 'Lấy lại' ],
                [ 'type' => 'delete', 'name' => 'Xóa' ],
            ],
        ],
        'settings' => [
            'name'          => 'Cấu hình',
            'permision'     => [
                [ 'type' => 'type_heading', 'name' => 'Cấu hình loại Heading' ],
                [ 'type' => 'type_rewrite', 'name' => 'Viết lại' ],
                [ 'type' => 'type_write', 'name' => 'Viết thêm' ],
                [ 'type' => 'general_ai', 'name' => 'Tư duy cho AI' ],
            ],
        ],
    ]
];
