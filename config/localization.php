<?php

/**
 * Sayt tillari (2026-08-06) — MaktabGID uch tilda ishlaydi: o'zbekcha (standart),
 * ruscha, inglizcha. Bu config `App\Http\Middleware\SetLocale` va nav'dagi til
 * almashtirgich (`/til/{locale}`) tomonidan ishlatiladi.
 */
return [

    'default' => 'uz',

    'supported' => [
        'uz' => [
            'label' => "O'zbekcha",
            'native' => "O'zbekcha",
        ],
        'ru' => [
            'label' => 'Русский',
            'native' => 'Русский',
        ],
        'en' => [
            'label' => 'English',
            'native' => 'English',
        ],
    ],

];
