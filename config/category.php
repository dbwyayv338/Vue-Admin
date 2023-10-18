<?php

return [
    'models' => [
        'category_type' => App\Http\CategoryType::class,
        'category_item' => App\Http\CategoryItem::class,
    ],

    'table_names' => [
        'category_types' => 'category_types',
        'category_items' => 'category_items',
    ]
];
