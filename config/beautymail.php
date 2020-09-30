<?php

return [

    // These CSS rules will be applied after the regular template CSS

    'css' => [
        '.confirm-button a { font-size: 16px; }',
        'h1 { font-size: 22px; margin-top: 0; text-align: center; padding: 0 15px; }',
        'p { font-size: 16px; text-align: center; padding: 0 15px; }',
        '.bottom-notice { background-color: #999999; padding: 25px 0; margin: 0; }',
        '.bottom-notice p { color: #EEEEEE; font-size: 12px; }',
        'hr { padding: 0; margin: 0; }',
        '.devicewidthinner { margin: 0 auto; width: 100%; }',
        '.unsubscribe { color: #EEEEEE; }'
    ],

    'colors' => [

        'highlight' => '#fffade',
        'button'    => '#993fff',

    ],

    'view' => [
        'senderName'  => 'Codery',
        'reminder'    => null,
        'unsubscribe' => null,
        'address'     => null,

        'logo'        => [
            'path'   => '%PUBLIC%/images/logo.svg',
            'width'  => '160',
            'height' => '65',
        ],

        'twitter'  => null,
        'facebook' => null,
        'flickr'   => null,
    ],

];
