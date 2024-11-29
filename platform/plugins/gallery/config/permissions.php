<?php

return [
    [
        'name' => 'Gallery',
        'flag' => 'gallery.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'gallery.create',
        'parent_flag' => 'gallery.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'gallery.edit',
        'parent_flag' => 'gallery.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'gallery.destroy',
        'parent_flag' => 'gallery.index',
    ],

    [
        'name' => 'Gallery Items',
        'flag' => 'gallery-item.index',
        'parent_flag' => 'gallery.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'gallery-item.create',
        'parent_flag' => 'gallery-item.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'gallery-item.edit',
        'parent_flag' => 'gallery-item.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'gallery-item.destroy',
        'parent_flag' => 'gallery-item.index',
    ],
    [
        'name' => 'Gallery Settings',
        'flag' => 'gallery.settings',
        'parent_flag' => 'gallery-item.index',
    ],
];
