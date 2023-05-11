<?php

namespace App\Helpers;

class SharedAccessHelper
{
    const READ = 0;
    const READWRITE = 1;

    const permissions = [
        [
            'name' => 'Read',
            'value' => self::READ
        ],
        [
            'name' => 'Read & Write',
            'value' => self::READWRITE
        ]
    ];
}
