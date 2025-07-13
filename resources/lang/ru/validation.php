<?php
return [
    'required' => 'Поле :attribute обязательно.',
    'string' => 'Поле :attribute должно быть строкой.',
    'max' => [
        'string' => 'Поле :attribute не может быть длиннее :max символов.',
    ],
    'unique' => 'Поле :attribute должно быть уникальным.',
    'attributes' => [
        'name' => 'название',
        'parent_id' => 'родительское меню',
    ],
    'exists' => [
        'name' => 'название',
        'menu_id' => 'родительское меню',
    ],
];
